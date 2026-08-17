import axios from "axios";
const api = axios.create({
    baseURL : "http://127.0.0.1:8000/api",
    //withCredentials: true,  KANDIROHA IDA KONA GHANKHDMO B COOKIES
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        // Authorization: `Bearer ${localStorage.getItem("token")}`, // KANDIROHA IDA KONA GHANKHDMO B TOKEN
    }
});
// Interceptor kayzid token automatiquement lkol request,
api.interceptors.request.use(
    (config)=> {
        const token = localStorage.getItem("token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);
export default api;

/* OLD CODE DYAL AXIOS 

    import axios from 'axios';

    export default axios.create({
        baseURL: 'http://127.0.0.1:8000/api', // 
    });
*/
