<template>
  <div class="header">
    <NuxtLink to="/" class="logo">
      <span class="logo__title">Глоссарий</span>
      <span class="logo__subtitle">терминов</span>
    </NuxtLink>

    <div class="login">
      <template v-if="Object.keys(currentUser).length">
        <span class="login__name">
          {{ currentUser.name }}
        </span>
        <button
          v-if="currentUser.is_admin"
          type="button"
          class="btn btn-primary mr-2"
          @click="$bvModal.show('createTerm')"
        >
          Создать термин
        </button>
        <button type="button" class="btn btn-danger" @click="logout">
          Выйти
        </button>
      </template>
      <template v-else>
        <button
          type="button"
          class="btn btn-primary"
          @click="$bvModal.show('login')"
        >
          Войти
        </button>
      </template>
    </div>

    <LoginModal />
  </div>
</template>

<script>
export default {
  components: {
    LoginModal: () => import('~/components/Modals/LoginModal'),
  },
  computed: {
    currentUser() {
      return this.$store.getters['user/currentUser']
    },
  },
  methods: {
    logout() {
      this.$cookies.remove('auth_token')
      this.$store.dispatch('user/logout')
    },
  },
}
</script>

<style lang="scss">
.logo {
  text-decoration: none !important;
  transition: 0.2s ease;
  display: flex;
  flex-direction: column;

  &:hover {
    opacity: 0.8;
  }

  &__title {
    background: #0163ff;
    padding: 8px 12px;
    color: #fff;
    text-transform: uppercase;
    font-weight: bold;
    border-radius: 2px;
    font-family: 'Open Sans', sans-serif;
    width: 150px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
  }

  &__subtitle {
    background: #e9e9e9;
    padding: 4px 12px;
    width: 115px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    text-transform: uppercase;
    font-weight: bold;
    color: #555;
    font-family: 'Open Sans', sans-serif;
  }
}

.login {
  margin-top: 15px;
  display: flex;
  align-items: center;
  flex-wrap: wrap;

  &__name {
    margin: 10px 15px 10px 0;
  }
}
</style>
