<template>

  <div class="modal fade" id="new-message-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div v-html="messageCallback"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>

  
  <div class="container">


    <form @submit.prevent="submitForm">
      <div>
        <h3 class="m-2">Write new message</h3>
        <textarea class="form-control" v-model="message" rows="3" placeholder="Type here..."></textarea>
      </div>
      <div>
        <button class="btn btn-light m-2" type="submit">Send message</button>
      </div>
    </form>
  </div>

</template>

<script>
import axios from 'axios';
import {Modal} from 'bootstrap'

export default {

  data() {
    return {
      message: '',
      messageCallback: '',
    }
  },
  methods: {

    showModal() {
      let myModal = new Modal(document.getElementById('new-message-modal'));
      myModal.show();
    },
    submitForm() {

      axios.post('/api/message/', {message: this.message})
          .then(response => {
            this.messageCallback = 'Message created with uuid: <b>' + response.data + '</b>';
          })
          .catch(error => {
            this.messageCallback = 'Message not send, error: ' + error;
          });

      this.showModal();
    }
  },

};
</script>
<style scoped>

</style>