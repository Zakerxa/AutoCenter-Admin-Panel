import axios from 'axios';

// export const API_URL = `http://localhost:8000/api`

const authorize = axios.create({
    withCredentials: true,
    credentials: "same-origin",
    // baseURL: API_URL
})

// Add a request interceptor
authorize.interceptors.request.use(config => {
    let token = localStorage.getItem('adminThida');
    token ? config.headers['Authorization'] = `Bearer ${token}` : console.log('401 Unauthorized');
    config.headers['X-Requested-With'] = "XMLHttpRequest";
    return config;
}, err => Promise.reject(err));

export default authorize;