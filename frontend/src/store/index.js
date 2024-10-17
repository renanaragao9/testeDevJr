import { createStore } from 'vuex';

export default createStore({
  state: {
    isAuthenticated: false,
    user: null,
  },
  mutations: {
    setUser(state, user) {
      state.isAuthenticated = true;
      state.user = user;
    },
    logout(state) {
      state.isAuthenticated = false;
      state.user = null;
    }
  },
  actions: {
    login({ commit }, user) {
      commit('setUser', user); // Ao logar, define o usuário
    },
    logout({ commit }) {
      commit('logout'); // Ao fazer logout, limpa o estado
    }
  },
  getters: {
    isAuthenticated: (state) => state.isAuthenticated,
    user: (state) => state.user
  }
});
