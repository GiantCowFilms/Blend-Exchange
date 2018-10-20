const state = {
    user: null
}

const getters = {

}

const actions = {
    async ['GET_USER'] (context,id) {
        let response = await blendExchange.getEndpoint(`/users/${id}`);
        let user = response.data;
        context.commit('SET_USER',response.data);
    },
    ['UPDATE_USER'] (context, user) {
        context.commit('SET_USER',user);
    }
}

const mutations = {
    ['SET_USER'] (state, user) {
        state.user = user;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}