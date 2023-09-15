<template>

  <div class="modal fade" id="show-message-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Message details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul>
            <li class="m-2">UUID:
              <div v-html="uuid"></div>
            </li>
            <li class="m-2"> Message:
              <div v-html="content"></div>
            </li>
            <li class="m-2"> Created At:
              <div v-html="createdAt"></div>
            </li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="text-center"><h3>List of messages</h3></div>

    <div id="dataGridContainer">
      <div id="dataGrid">
        <DxDataGrid
            :show-borders="true"
            :data-source="list">
          <DxSorting mode="multiple"/>
          <DxColumn
              data-field="name"
          />
          <DxColumn
              :width="150"
              data-field="created_at"
              data-type="datetime"
          />
          <DxColumn
              caption="Details"
              type="buttons">
            <DxButton :icon="showDetailsIcon"
                      @click="showDetails"
                      cssClass="show-details-button"
            />
          </DxColumn>
        </DxDataGrid>
      </div>
    </div>
  </div>

</template>

<script>
import axios from 'axios';
import 'devextreme/dist/css/dx.light.css';
import {
  DxDataGrid,
  DxColumn,
  DxSorting,
  DxButton
} from 'devextreme-vue/data-grid'
import showDetailsIcon from '../images/show_details.svg';
import {Modal} from "bootstrap";


export default {
  data() {
    return {
      list: [],
      showDetailsIcon: showDetailsIcon,
      uuid: '',
      content: '',
      createdAt: '',
    };
  },
  mounted() {
    axios.get('/api/message_list/')
        .then(response => {
          this.list = response.data;
        })
        .catch(error => {
          console.error('Error:', error);
        });
  },
  methods: {

    showDetails(e) {
      let uuid = e.row.data.name.replace('_message.json', '');
      axios.get('/api/message/' + uuid)
          .then((response) => {
            this.uuid = response.data.uuid;
            this.content = response.data.content;
            this.createdAt = response.data.createdAt;
          })

      let myModal = new Modal(document.getElementById('show-message-modal'));
      myModal.show();
    }
  },
  components: {
    DxDataGrid,
    DxColumn,
    DxSorting,
    DxButton
  }
};
</script>


<style scoped>

#dataGridContainer {
  display: flex;
  justify-content: center;
}

#dataGrid {
  width: 30%;
}

.show-details-button {
  cursor: grab;
}


</style>