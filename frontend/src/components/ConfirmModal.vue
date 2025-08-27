<template>
    <b-modal
      :id="modalId"
      :title="title"
      :ok-title="okText"
      :cancel-title="cancelText"
      :ok-variant="okVariant"
      @ok="handleOk"
    >
      <slot></slot>
    </b-modal>
  </template>
  
  <script>
  export default {
    name: 'ConfirmModal',
    props: {
      modalId: {
        type: String,
        required: true
      },
      title: {
        type: String,
        default: 'Confirmação'
      },
      okText: {
        type: String,
        default: 'OK'
      },
      cancelText: {
        type: String,
        default: 'Cancelar'
      },
      okVariant: {
        type: String,
        default: 'primary'
      }
    },
    methods: {
     
      handleOk() {
        this.$emit('confirmed');
      },
     
      show() {
        this.$bvModal.show(this.modalId);
      },
      hide() {
        this.$bvModal.hide(this.modalId);
      }
    }
  };
  </script>
