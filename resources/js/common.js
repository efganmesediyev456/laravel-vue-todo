export default {
    data(){
        return {

        }
        
    },
    
    methods: {
         async callApi(method, url, data){

            try {
               
              return  axios({
                    method: method,
                    url: url,
                    data: data
                    });
            } catch (error) {
                return error.response
            }
            // Send a POST request
               

        },
        i(nodesc,title) {
            
            this.$Notice.info({
                title: title,
                desc: nodesc
            });
        },
        s(nodesc,title) {
            this.$Notice.success({
                title: title,
                desc: nodesc
             });
        },
        w(nodesc,title) {
            this.$Notice.warning({
                title: title,
                desc: nodesc
              });
        },
        e(nodesc,title) {
            this.$Notice.error({
                title: title,
                desc: nodesc
              });
        },
        
        
    },

    created(){
        this.$Notice.config({
            top: 100,
            duration: 3
        });
    }


   
}