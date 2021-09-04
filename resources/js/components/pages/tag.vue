<template>
  <div class="content-wrapper">
    <Modal
        v-model="addModal"
        title="Add Tag Modal" :closable="false" :mask-closeable="false">
        <p>
           <Input v-model="data.name" placeholder="tag adi daxil et"  />
        </p>
        <div slot="footer">
          <Button type="success" @click="addTag" :disabled="isLoading" :loading="isLoading">Ok</Button>
          <Button type="error" @click="addModal=false" >No</Button>
        </div>
    </Modal>


    <Modal
        v-model="editModal"
        title="Edit Tag Modal" :closable="false" :mask-closeable="false">
        <p>
           <Input v-model="editdata.name" placeholder="edit ucun tag adi daxil et"  />
        </p>
        <div slot="footer">
          <Button type="success" @click="editTag" :disabled="isEditLoading" :loading="isLoading">{{ isEditLoading ? 'Editing...':'Edit' }}</Button>
          <Button type="error" @click="editModal=false" >No</Button>
        </div>
    </Modal>

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 @click="addModal=true">Tag DataTables<Button type="default"><Icon type="ios-add" />Add Tag</Button></h1>
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
                    <th>Tag Name</th>
                    <th>Created At</th>
                    <th>Uptated at</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                
                  <tr v-for="(tag,i) in tags" :key="i">
                    <td>{{ tag.id }}</td>
                    <td>{{tag.name}}
                    </td>
                    <td>{{ tag.created_at }}</td>
                    <td>{{ tag.updated_at }}</td>
                    <td>
                       
    <Button type="success" size="small" @click="editModalData(tag, i)">Edit<Icon type="ios-backspace" /></Button>
    <Button type="error" size="small" :loading="tag.isLoading" @click="tagDelete(tag,i)">Delete<Icon type="ios-trash" /></Button>
                    
                    </td>
                  </tr>

                 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Tag Name</th>
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
      },
      editdata:{
          name:'',
      },
      addModal:false,
      isLoading:false,
      isEditLoading:false,
      editModal:false,
      tags:[],
      index:-1,
      
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

    mounted() {
                        
                        
            },
created(){
                
                

                  

},
methods:{
   async addTag(){
    
   let _this = this
    this.isLoading=true;
    const rest= await this.callApi('post','/app/tags_create',this.data).catch(function (error) {
    if (error.response.status==422) {
      _this.i(error.response.data.errors.value[0],'Xeta');
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

  async editTag(){


    this.isEditLoading=true;
    let _this = this
   
    const rest= await this.callApi('post','/app/tags_edit',this.editdata).catch(function (error) {
     
    if (error.response.status==422) {
      _this.i(error.response.data.errors.value[0],'Xeta');
    }
    });
    if(rest.status===200){
      this.tags[this.index].name=this.editdata.name;
        // this.tags[this.index]=this.editdata.value;
        this.s('Ugurla deyisdirildi','Bravo');
        this.isEditLoading=false;
        this.editModal=false;
    } 


  },
  editModalData(tag, i){
    
    this.index=i;
    
    let obj={
      id:tag.id,
      name:tag.name,
    }
    this.editdata=obj;
    this.editModal=true;
  },

  async tagDelete(tag, i){
      if(!confirm('eminsenmi???')) return;
      this.$set(tag,'isLoading',true);
      const rest=await this.callApi('post','/tag/delete',tag);
    if(rest.status===200){
        this.tags.splice(i,1);
        this.s('Ugurla silindi','Bravo');
    } 


  }
},
 async created(){
    const tags=await this.callApi('get','/app/get_tags','');
   
    if(tags.status===200){
      this.tags=tags.data;
    }
  },
  
}
</script>
