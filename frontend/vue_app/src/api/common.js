import axios from 'axios'

if (localStorage.getItem("auth_token")) {
    axios.defaults.headers.common['Authorization'] =
        "Token " + localStorage.getItem("auth_token")
}

export const HTTP = axios.create({
    baseURL: "http://192.168.0.5:8000",
    headers: {
        "Content-type": "application/json",
    }
})