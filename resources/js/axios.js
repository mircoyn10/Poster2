// axios.js
import axios from 'axios';

// Imposta Axios per includere il token
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default axios;
