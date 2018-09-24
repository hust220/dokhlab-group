import axios from 'axios'

export default {
  get (url, cb1, cb2) {
    axios({
      method: 'get',
      url: url,
      withCredentials: true
    }).then(response => {
      console.log(response)
    }).catch(error => {
      console.log(error.response)
    })
  },

  post (url, cb1, cb2) {
    axios({
      method: 'post',
      url: url,
      withCredentials: true
    }).then(response => {
      console.log(response)
    }).catch(error => {
      console.log(error.response)
    })
  }
}
