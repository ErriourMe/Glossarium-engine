export const actions = {
  async nuxtServerInit(store, context) {
    if (context.app.$cookies.get('auth_token')) {
      try {
        const credintails = await this.$axios.post(
          `${process.env.API_ROUTE}/${process.env.API_VERSION}/auth/me`
        )
        store.commit('user/refreshed', credintails.data.data.credintails.user)
      } catch (err) {
        if (err.response?.status === 401) {
          store.dispatch('user/logout')
        }
      }
    }
  },
}
