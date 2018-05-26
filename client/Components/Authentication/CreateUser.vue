<template>
<div style="max-width: 480px; margin: auto;">
            <h2>Setup Your Account:</h2>
            <form name="createUser" id="registerForm" v-on:submit.prevent="submit(user,null,form)">
                            <ajax-error input="email"></ajax-error>
            <input v-model="user.email" class="txtBlue bodyStack" placeholder="Email" autofocus>
            <div class="bodyStack">
                 <input v-model="user.usePassword" type="checkbox" /> Use Password For Extra Security
            </div>
            <ajax-error input="password"  v-if="user.usePassword"></ajax-error>
            <input type="password" v-model="user.password" v-if="user.usePassword" class="txtBlue bodyStack" placeholder="Password"/>
            <div class="bodyStack">
                        <ajax-error input="termsAndPrivacy"></ajax-error>
            <input v-model="user.termsAndPrivacy" class="" type=checkbox>I agree to the Blend-Exchange <a href="/terms">Terms of Service</a> and have read the <a href="/privacy">Privacy Policy</a>.
            </div>
            <ajax-button :form="form">
                Continue
            </ajax-button>
            </form>

    <!-- <ajax-form name="createUser" id="registerForm" :action='`/users/${user.id}/setup`' v-on:completedAjaxUpload="completedAjaxUpload" >
        <template slot-scope="ajax">
            <h1>Setup Account</h1>

            <button type="submit" class="btnBlue bodyStack">
                Continue
            </button>

        </template>
    </ajax-form> -->
</div>
</template>
<script>
import AjaxForm from "@/Mixins/AjaxForm";

export default {
  mixins: [AjaxForm],
  props: {
    user: {
      required: true,
      type: Object
    },
    token: {
      required: true,
      type: String
    }
  },
  data () {
    return {
    };
  },
  mounted () {
    this.setEndpoint(`/users/${this.user.id}/setup`);
    this.setAuthentication(this.token);
  },
  computed: {
    authCode() {
      return this.$route.query.code;
    }
  },
  methods: {
    completedAjaxUpload(data) {
      this.$router.push({ name: 'UserPage', props: { id: data.id } });
    }
  }
};
</script>
    