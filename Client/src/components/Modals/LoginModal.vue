<template>
  <b-modal
    id="login"
    hide-footer
    hide-header
    header-class="hide-modal-header"
    centered
    size="xlg"
  >
    <div class="login-content">
      <div class="d-flex flex-column justify-content-between h-100">
        <div
          v-if="step === 'login'"
          class="d-flex flex-column justify-content-center h-100"
        >
          <div>
            <button
              v-if="step !== 'login'"
              class="login-content__back-btn mb-4 ml-n1"
              :data-step="prevStep"
              @click="changeStep(prevStep, true)"
            >
              <SvgIcon name="arrow-right" class="rotate--180" />
              <span class="ml-2">Назад</span>
            </button>
          </div>

          <h2>Вход по почте</h2>

          <div class="login-content__methods">
            <form @submit.prevent="login">
              <InputField
                id="login_email"
                v-model="localForm.login.email"
                title="Почта"
                :validation="validation.login.email"
                @input="clearValidation('login', 'email')"
              />
              <InputField
                id="login_password"
                v-model="localForm.login.password"
                title="Пароль"
                type="password"
                :validation="validation.login.password"
                @input="clearValidation('login', 'password')"
              />

              <div class="form-group">
                <button
                  class="d-block btn btn-primary login-content__button"
                  type="submit"
                >
                  <span>Войти</span>
                </button>
              </div>
              <div class="d-flex alig-items-center justify-content-between">
                <a
                  class="login-content__form-link"
                  @click="changeStep('register')"
                >
                  Зарегистрироваться
                </a>
              </div>
            </form>
          </div>
        </div>

        <div
          v-if="step === 'register'"
          class="d-flex flex-column justify-content-center h-100"
        >
          <div>
            <button
              v-if="step !== 'methods'"
              class="login-content__back-btn mb-4 ml-n1"
              :data-step="prevStep"
              @click="changeStep(prevStep, true)"
            >
              <SvgIcon name="arrow-right" class="rotate--180" />
              <span class="ml-2">Назад</span>
            </button>
          </div>

          <h2>Регистрация</h2>

          <div class="login-content__methods">
            <form @submit.prevent="register">
              <InputField
                id="register_name"
                v-model="localForm.register.name"
                title="Имя"
                :validation="validation.register.name"
                @input="clearValidation('register', 'name')"
              />

              <InputField
                id="register_email"
                v-model="localForm.register.email"
                title="Почта"
                :validation="validation.register.email"
                @input="clearValidation('register', 'email')"
              />

              <InputField
                id="register_password"
                v-model="localForm.register.password"
                title="Пароль"
                type="password"
                :validation="validation.register.password"
                @input="clearValidation('register', 'password')"
              />

              <div class="form-group">
                <button
                  class="d-block btn btn-primary login-content__button"
                  type="submit"
                >
                  <span>Зарегистрироваться</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="login-content__legal flex-shrink-0">
          <template v-if="step === 'register'">При регистрации</template>
          <template v-else-if="step === 'restore'">
            При восстановлении доступа
          </template>
          <template v-else>При входе</template>
          вы автоматически соглашаетесь с
          <a href="#">правилами пользования сайтом</a> и
          <a href="#">даёте согласие на обработку персональных данных</a>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  data() {
    return {
      localForm: {
        login: {},
        register: {},
        restore: {},
      },
      step: 'login',
      prevStep: ['login'],
      validation: {
        login: {},
        register: {},
        restore: {},
      },
    }
  },
  computed: {
    currentUser() {
      return this.$store.getters['user/currentUser']
    },
  },
  methods: {
    clearValidation(element, field) {
      if (this.validation[element][field]) {
        delete this.validation[element][field]
      }
    },
    login() {
      this.$axios
        .post(
          `${process.env.API_ROUTE}/${process.env.API_VERSION}/auth/login`,
          this.localForm.login
        )
        .then((response) => {
          this.$store.dispatch(
            'user/logined',
            response.data.data.credintails.user
          )
          this.$cookies.set(
            'auth_token',
            response.data.data.credintails.access_token,
            {
              path: '/',
              maxAge: response.data.data.credintails.expires_in,
            }
          )

          this.$bvModal.hide('login')
          this.localForm.login = {}
        })
        .catch((error) => {
          if (typeof error.response.data.validation === 'object') {
            this.validation.login = error.response.data.validation
          }
        })
    },
    register() {
      this.$axios
        .post(
          `${process.env.API_ROUTE}/${process.env.API_VERSION}/auth/register`,
          this.localForm.register
        )
        .then((response) => {
          console.log(response)
        })
        .catch((error) => {
          if (typeof error.response.data.validation === 'object') {
            this.validation.register = error.response.data.validation
          }
        })
    },
    changeStep(step, back = false) {
      if (back) {
        this.step = this.prevStep.pop() || 'login'
      } else {
        this.prevStep.push(this.step)
        this.step = step
      }
    },
  },
}
</script>

<style lang="scss">
.login-content {
  position: relative;

  &__legal {
    margin-top: 28px;
    padding-top: 20px;
    font-size: 12px;
    line-height: 1.5em;
    color: #949494;
    position: relative;

    a {
      color: #565656;
      text-decoration: underline;

      &:hover {
        color: #1a6fde;
      }
    }
  }

  &__button {
    width: 100%;
    padding: 10px 0;
    display: flex;
    align-items: center;
    cursor: pointer;

    & + & {
      margin-top: 12px;
    }
  }

  &__methods {
    margin-top: 20px;
  }

  &__back-btn {
    background: #fbfbfb;
    font-size: 0.8rem;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 0;
    border-radius: 6px;
    transition: 0.2s ease;
    border: none;

    svg {
      width: 12px;
      height: 12px;
    }

    &:hover {
      background: #f3f3f3;
    }
  }

  &__form-link {
    text-align: center;
    display: flex;
    font-size: 0.8rem;
    cursor: pointer;
  }
}
</style>
