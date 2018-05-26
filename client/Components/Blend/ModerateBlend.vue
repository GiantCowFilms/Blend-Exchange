<template>
    <div v-if="isAuthenticated && isAdministrator">
        <modal name='deleteBlend' classes='modal' :pivotY='0.2' height="auto">
            <h2>
                Delete Blend:
            </h2>
            <delete-blend-form :deleteMode="deleteMode" :blend="blend"></delete-blend-form>
        </modal>
        <modal name='adminComment' classes='modal' :pivotY='0.2' height="auto">
            <h2>
                Admin Comment:
            </h2>
            <admin-comment-form :blend="blend"></admin-comment-form>
        </modal>
        <div>
            <button class="btnBlue" v-on:click="$modal.show('deleteBlend'),deleteMode = 'soft'">Soft Delete</button>
            <button class="btnBlue" v-on:click="$modal.show('deleteBlend'),deleteMode = 'hard'">Hard Delete</button>
            <button class="btnBlue" v-on:click="$modal.show('adminComment')">Edit Comment</button>
        </div>
        <div>
            <h3>Flags:</h3>
            <index-flag :flags="blend.flags"></index-flag>
        </div>
    </div>
</template>
<script>
import indexFlag from '@C/Flag/IndexFlag'
import { mapGetters } from 'vuex'
import DeleteBlendForm from '@C/Blend/DeleteBlendForm'
import AdminCommentForm from '@C/Blend/AdminCommentForm'

export default {
    components: {
        indexFlag,
        DeleteBlendForm,
        AdminCommentForm
    },
    computed: {
        ...mapGetters([
            'isAdministrator',
            'isAuthenticated'
        ])
    },
    props: {
        blend: {
            required:true,
            type: Object
        }
    },
    data () {
        return {
            deleteMode: 'soft'
        };
    }
}
</script>
