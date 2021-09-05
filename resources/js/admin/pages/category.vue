<template>
  <div class="content-wrapper">
    <Modal
        v-model="addModal"
        title="Add Category Modal" :closable="false" :mask-closeable="false">
        <p>
           <Input v-model="data.name" placeholder="category adi daxil et"  />
        </p>
        <br>
        <p>
            <Upload ref="upload"
                :headers="{'x-csrf-token':token,'X-Requested-With':'XMLHttpRequest'}"
                :on-success="handleSuccess"
                :on-error="handleError"
               
                type="drag"
                action="/app/upload">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                </div>
            </Upload>
            <div v-if="data.imageName.length > 0">
                <Button type="error" @click="deletePhoto(data.imageName,false)">sekli sil</Button>
                <img style="width: 100%; height: auto;" :src="`/uploads/${data.imageName}`"  />
            </div>
        </p>
        <div slot="footer">
          <Button type="success" @click="addTag" :disabled="isLoading" :loading="isLoading">Ok</Button>
          <Button type="error" @click="addModalShow(false)" >No</Button>
        </div>
    </Modal>


  


    <Modal
        v-model="editModal"
        title="Edit Category Modal" :closable="false" :mask-closeable="false">
        Category Name <br> 
        <p>
           <Input  v-model="editdata.name" placeholder="edit ucun category adi daxil et"  />
        </p>
        <br> IMage Upload <br>
        <P>
           <div v-if="!editShowDrag">
                <Button type="error" @click="deletePhoto(editdata.imageName,true)">sekli sil</Button>
                <img style="width: 100%; height: auto;" :src="`/uploads/${editdata.imageName}`"  />
           </div>

             <Upload ref="editUpload" v-if="editShowDrag"
                :headers="{'x-csrf-token':token,'X-Requested-With':'XMLHttpRequest'}"
                :on-success="handleSuccess"
                :on-error="handleError"
                type="drag"
                action="/app/upload">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                </div>
            </Upload>
        </P>
        <div slot="footer">
          <Button type="success" @click="editCat" :disabled="isEditLoading" :loading="isLoading">{{ isEditLoading ? 'Editing...':'Edit' }}</Button>
          <Button type="error" @click="addModalShow(true)" >No</Button>
        </div>
    </Modal>

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 @click="addModalFunc">Tag DataTables<Button type="default"><Icon type="ios-add" />Add Tag</Button></h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
                <!-- Main content -->
               <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Cat Name</th>
                    <th>Cat Image</th>
                    <th>Created At</th>
                    <th>Uptated at</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                
                  <tr v-for="(tag,i) in tags" :key="i">
                    <td>{{ tag.id }}</td>
                    <td>{{tag.name}}</td>
                    <td><img :src="`/uploads/${tag.image}`" style="width:50%; height:auto;" /></td>
                    <td>{{ tag.created_at }}</td>
                    <td>{{ tag.updated_at }}</td>
                    <td>
                       
    <Button type="success" size="small" @click="editModalData(tag, i)">Edit<Icon type="ios-backspace" /></Button>
    <Button type="error" size="small" :loading="tag.isLoading" @click="catDelete(tag,i)">Delete<Icon type="ios-trash" /></Button>
                    
                    </td>
                  </tr>

                 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Cat Name</th>
                    <th>Cat Image</th>
                    <th>Created At</th>
                    <th>Uptated at</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
     </div>
          </div>
        </div>
      </div>
    </section>
    
    

  </div>
</template>

     

<script>



export default {
  data(){
    return {
      data:{
        name:'',
        imageName:''
      },
      
      editdata:{
          name:'',
          imageName:'',
      },
      editShowDrag:false,
      addModal:false,
      isLoading:false,
      isEditLoading:false,
      editModal:false,
      tags:[],
      index:-1,
      token:''
      
    }
  },
 watch: {
        tags() {
            if (this.dataTable) {
                this.dataTable.destroy();
            }
            this.$nextTick(() => {
                this.dataTable = $("#example1").DataTable({
                    language: {
                        emptyTable: "No Results Found"
                    },
                    "order": [[ 0, "desc" ]]
                });
            });
        }
    },


methods:{
   async addTag(){
    
    let _this = this
    this.isLoading=true;
    const rest= await this.callApi('post','/app/cats_create',this.data).catch(function (error) {
    if (error.response.status==422) {
        _this.isLoading=false;
        if(error.response.data.errors.imageName){
           
            _this.i(error.response.data.errors.imageName[0],'Xeta');
        }
        if(error.response.data.errors.name){
            _this.i(error.response.data.errors.name[0],'Xeta');
        }
    }
    });
    if(rest.status===201){
        this.data.name=null;
        this.s('Ugurla elave edildi','Bravo');
        this.isLoading=false;
        this.addModal=false;
        this.tags.unshift(rest.data);
    } 
    
  },

  async editCat(){
    
    this.isEditLoading=true;
    
    let _this = this
   
    const rest= await this.callApi('post','/app/cats_edit',this.editdata).catch(function (error) {
     
    if (error.response.status==422) {
      _this.i(error.response.data.errors.value[0],'Xeta');
    }
    });
    if(rest.status===200){
      this.tags[this.index].name=this.editdata.name;
        this.tags[this.index].image=this.editdata.imageName;
        this.s('Ugurla deyisdirildi','Bravo');
        this.isEditLoading=false;
        this.editModal=false;
    } 
  },
  addModalShow(value){
    if(value){
      this.editModal=false;
      this.editShowDrag=false;
    }else{
      this.addModal=false;
    }
  },
  addModalFunc(){
        this.addModal=true;
        this.data.imageName='';
  },
  editModalData(tag, i){

    this.index=i;
    let obj={
      id:tag.id,
      name:tag.name,
      imageName:tag.image
    }
    this.editdata=obj;
    this.editModal=true;
  },

  async catDelete(tag, i){
      if(!confirm('eminsenmi???')) return;
      this.$set(tag,'isLoading',true);
      const rest=await this.callApi('post','/cat/delete',tag);
    if(rest.status===200){
        this.tags.splice(i,1);
        this.s('Ugurla silindi','Bravo');
    } 


  },
  handleSuccess(res, file){
    this.editShowDrag=true;
    this.data.imageName=res;
    this.editdata.imageName=res;
    this.editShowDrag=false;
    

  },

  handleError(res,file){
       this.e(file.errors.file[0],'Xeta');
  },
  async deletePhoto(data,iseditoradd){
  
    
      const test=await this.callApi('post','/delete/photo',{imageName:data});
      
      if(test.status==200){
        
        if(iseditoradd){
          this.editdata.imageName='';
            this.editShowDrag=true;
        }else{
             this.data.imageName='';
              this.$refs.upload.clearFiles();
        }
         
         
      }
  }
  
},
 async created(){

    this.token=window.Laravel.csrfToken;
    const tags= await this.callApi('get','/app/get_categories','');
    
   
    if(tags.status===200){
      this.tags=tags.data;
    }
    
  },

  
  
}
</script>
