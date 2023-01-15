import Vuex from 'vuex';

export default new Vuex.Store({
  state: {
    abcde: 'teste de state',
    item: {},
    transacao: {
        status: '',
        mensagem: '',
    }
  },
  getters: {
    //aqui você pode definir os getters do seu store
  },
  mutations: {
    //aqui você pode definir as mutations do seu store
  },
  actions: {
    //aqui você pode definir as actions do seu store
  },
});
