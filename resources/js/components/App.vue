<template>
    <div class="w-50 mb-3 p-5 m-auto">
        <div class="">
            <input v-model="title" class="mb-3 form-control" type="text" placeholder="title">
            <div ref="dropzone" class="btn d-block mb-3 p-5 bg-dark text-center text-light">
                Upload
            </div>
            <div class="mb-3">
                <vue-editor
                    id="editor"
                    useCustomImageHandler
                    @image-added="handleImageAdded"
                    v-model="content"
                >
                </vue-editor>
            </div>
            <input @click.prevent="store" class="btn btn-success" type="submit" value="add">
        </div>



        <div class="mt-5" v-if="post">
            <div>
                <h4>{{ post.title }}</h4>
                <div v-for="image in post.images">
                    <img :src="image.preview_url">
                </div>
                <div class="ql-editor" v-html="post.content"></div>
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone';
import { VueEditor } from 'vue2-editor';

export default {
    name: "App",

    data() {
        return {
            dropzone: null,
            title: null,
            post: null,
            content: null,
        }
    },

    components: {
        VueEditor
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/posts//api/posts/',
            autoProcessQueue: false,
        })

        this.getPost()
    },

    methods: {
        store() {
            const data = new FormData()
            const files = this.dropzone.getAcceptedFiles()

            data.append('title', this.title)
            data.append('content', this.content)

            files.forEach(file => {
                data.append('images[]', file)
                this.dropzone.removeFile(file)
            })

            this.title = ''
            this.content = ''
            axios.post('/api/posts/', data).then(response => {
                this.getPost()
            })
        },

        getPost() {
            axios.get('/api/posts/').then(response => {
                console.log(response.data.data);
                this.post = response.data.data
            })
        },

        handleImageAdded(file, Editor, cursorLocation, resetUploader) {
            const formData = new FormData();
            formData.append("image", file);

            axios.post('/api/posts/images', formData)
                .then(result => {
                    const url = result.data.url; // Get url from response
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
}
</script>

<style scoped>

</style>
