import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    showTaskForm: false
  },
  mutations: {
    toggleTaskForm(state) {
      state.showTaskForm = !state.showTaskForm;
    },
    hideTaskForm(state) {
      state.showTaskForm = false;
    }
  },
  actions: {
    toggleTaskForm({ commit }) {
      commit('toggleTaskForm');
    },
    hideTaskForm({ commit }) {
      commit('hideTaskForm');
    }
  },
  getters: {
    showTaskForm: state => state.showTaskForm
  }
});
