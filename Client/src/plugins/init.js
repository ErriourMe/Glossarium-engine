export default function ({ $axios, store, app, route }) {
  if (app.$cookies.get('auth_token')) {
    $axios
      .post(`${process.env.API_ROUTE}/${process.env.API_VERSION}/auth/me`)
      .then((res) => {
        const userData = res.data.data.credintails.user
        store.dispatch('user/refreshed', userData)

        if (process.client) {
          console.log(
            '%c%s',
            `
                background-color: red;
                color: #fff;
                padding: 10px 20px;
                font-size: 18px;
                border-radius: 5px;
              `,
            'Не передавайте данные из консоли посторонним лицам, это может быть небезопасно'
          )
        }
      })
      .catch((err) => {
        console.log(err)
        if (err.response?.status === 401) {
          app.$cookies.remove('token')
          store.dispatch('user/logout')
        }
      })
  } else {
    store.dispatch('user/logout')
  }
}
