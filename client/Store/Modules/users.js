const state = {
    user: null
}

const getters = {
    getCurrentUser: state => {
        return state.user;
    }
}

const actions = {
    async ['GET_USER'] (context,id) {
        const response = await blendExchange.getEndpoint(`/users/${id}`);
        const user = response.data;
        context.commit('SET_USER',user);
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