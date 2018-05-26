import axios from 'axios'
import blendExchange from '@/Api/BlendExchangeApi'
const state = {
    all: [],
    blend: null
}

const getters = {
    flagWarnings (state) {

    }
}

const actions = {
    getBlends({commit},query) {
        blendExchange.getEndpoint(`/blends`,{
            params: query
        }).then((data) => {
            commit('setBlends', data);
        })
    },
    getBlend({commit},{id}) {
        axios.get(`/api/blends/${id}`).then(({data}) => {
            commit('setBlend', data.data);
        })
    }
}

const mutations = {
    setBlends (state,blends) {
        state.all = blends
    },
    setBlend(state,blend) {
        if (!state.all.some(x => {x.id === blend.id})) {
            state.all.push(blend);
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}