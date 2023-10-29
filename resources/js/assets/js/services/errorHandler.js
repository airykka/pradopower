import axios from 'axios'
import toast from './toast'

function errorResponseHandler(error) {
    // check for errorHandle config
    if( error.config.hasOwnProperty('errorHandle') && error.config.errorHandle === false ) {
        return Promise.reject(error);
    }

    // if has response show the error 
    if (error.response) {
        var message 
        if(error.response.status == 422 || error.response.status == 200 || error.response.status == 401 || error.response.status == 404) {
            if(error.response.data.errors) {
                let errors = error.response.data.errors
                let errorList = Object.values(errors)
                errorList.map(err => {
                    //toast.error(err)
                    message = err
                })
            }
            //console.log(`error.response`, error.response)
            toast.error(error.response.data.message || message);

        }
    }
}

// apply interceptor on response
axios.interceptors.response.use(
   response => response,
   errorResponseHandler
);

export default errorResponseHandler;