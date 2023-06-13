
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <!-- Modal  Add Space form-->
            <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center " @click.self="closeModal">
                <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                    <h1 class="mb-4 text-2xl font-semibold">Titre de article</h1>
                    <form @submit.prevent="addArticle" class="space-y-4 ">
                        <div>
                            <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                            <input v-model="articleTitle" type="text" id="title" name="title"
                                class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="min-h-auto flex flex-col">

                <div v-if="editor" class="cmds bg-black m-auto p-2 rounded ">
                    <button title="image" @click="addImage">
                        <i class="ri-image-add-line"></i>
                    </button>

                    <div class="divider"></div>

                    <button title="link" @click="toggleLink" :class="{ 'is-active': editor.isActive('link') }">
                        <i class="ri-link"></i>
                    </button>

                    <div class="divider"></div>

                    <button title="bold" @click="editor.chain().focus().toggleBold().run()"
                        :disabled="!editor.can().chain().focus().toggleBold().run()"
                        :class="{ 'is-active': editor.isActive('bold') }">
                        <i class="ri-bold"></i>
                    </button>
                    <button title="italic" @click="editor.chain().focus().toggleItalic().run()"
                        :disabled="!editor.can().chain().focus().toggleItalic().run()"
                        :class="{ 'is-active': editor.isActive('italic') }">
                        <i class="ri-italic"></i>
                    </button>
                    <button title="strike" @click="editor.chain().focus().toggleStrike().run()"
                        :disabled="!editor.can().chain().focus().toggleStrike().run()"
                        :class="{ 'is-active': editor.isActive('strike') }">
                        <i class="ri-strikethrough"></i>
                    </button>

                    <button @click="editor.chain().focus().toggleHighlight().run()"
                        :class="{ 'is-active': editor.isActive('highlight') }">
                        <i class="ri-mark-pen-line"></i>

                    </button>

                    <button title="code" @click="editor.chain().focus().toggleCode().run()"
                        :disabled="!editor.can().chain().focus().toggleCode().run()"
                        :class="{ 'is-active': editor.isActive('code') }">
                        <i class="ri-code-fill"></i>
                    </button>

                    <button title="clear marks" @click="editor.chain().focus().unsetAllMarks().run()">
                        <i class="ri-strikethrough-2"></i>
                    </button>

                    <div class="divider"></div>

                    <button title="clear nodes" @click="editor.chain().focus().clearNodes().run()">
                        <i class="ri-format-clear"></i>
                    </button>
                    <button title="paragraph" @click="editor.chain().focus().setParagraph().run()"
                        :class="{ 'is-active': editor.isActive('paragraph') }">
                        <i class="ri-paragraph"></i>
                    </button>
                    <button title="h1" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                        :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">
                        <i class="ri-h-1"></i>
                    </button>
                    <button title="h2" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                        :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">
                        <i class="ri-h-2"></i>
                    </button>
                    <button title="h3" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                        :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">
                        <i class="ri-h-3"></i>
                    </button>
                    <button title="h4" @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
                        :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }">
                        <i class="ri-h-4"></i>
                    </button>
                    <button title="h5" @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
                        :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }">
                        <i class="ri-h-5"></i>
                    </button>
                    <button title="h6" @click="editor.chain().focus().toggleHeading({ level: 6 }).run()"
                        :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }">
                        <i class="ri-h-6"></i>
                    </button>

                    <div class="divider"></div>

                    <button title="bullet list" @click="editor.chain().focus().toggleBulletList().run()"
                        :class="{ 'is-active': editor.isActive('bulletList') }">
                        <i class="ri-list-unordered"></i>
                    </button>
                    <button title="ordered list" @click="editor.chain().focus().toggleOrderedList().run()"
                        :class="{ 'is-active': editor.isActive('orderedList') }">
                        <i class="ri-list-ordered"></i>
                    </button>
                    <button title="code block" @click="editor.chain().focus().toggleCodeBlock().run()"
                        :class="{ 'is-active': editor.isActive('codeBlock') }">
                        <i class="ri-code-box-line"></i>
                    </button>
                    <button title="blockquote" @click="editor.chain().focus().toggleBlockquote().run()"
                        :class="{ 'is-active': editor.isActive('blockquote') }">
                        <i class="ri-double-quotes-l"></i>
                    </button>
                    <button title="horizontal rule" @click="editor.chain().focus().setHorizontalRule().run()">
                        <i class="ri-separator"></i>
                    </button>

                    <div class="divider"></div>

                    <button title="hard break" @click="editor.chain().focus().setHardBreak().run()">
                        <i class="ri-text-wrap"></i>
                    </button>
                    <button title="undo" @click="editor.chain().focus().undo().run()"
                        :disabled="!editor.can().chain().focus().undo().run()">
                        <i class="ri-arrow-go-back-line"></i>
                    </button>
                    <button title="redo" @click="editor.chain().focus().redo().run()"
                        :disabled="!editor.can().chain().focus().redo().run()">
                        <i class="ri-arrow-go-forward-line"></i>
                    </button>
                </div>

                <editor-content :editor="editor" class="overflow-auto" />
                <button @click="openModal"
                    class="bg-green-500 text-white px-4 py-2 rounded-full m-auto w-fit mt-2">Submit
                    Article</button>
            </div>
        </DashboardLayout>
    </div>
</template>
<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { RouterView, useRoute, useRouter } from 'vue-router';
import Link from '@tiptap/extension-link'
import Dropcursor from '@tiptap/extension-dropcursor'
import Image from '@tiptap/extension-image'
import Highlight from '@tiptap/extension-highlight'
import StarterKit from '@tiptap/starter-kit'
import { Editor, EditorContent } from '@tiptap/vue-3'
import { onBeforeUnmount, ref } from 'vue';
import axios from 'axios'



const editor = new Editor({
    extensions: [
        StarterKit,
        Highlight,
        Image,
        Dropcursor,
        Link
    ],
    content: ``,
})


const articleContent = ref(null);
const articleTitle = ref(null);

const isModalOpen = ref(false);
const route = useRoute();
const router = useRouter();
const addArticle = async () => {
    articleContent.value = editor.getHTML();
    await axios.post('/api/articles', {
        space_id: route.params.space_id,
        manual_id: route.params.manual_id,
        title: articleTitle.value,
        content: articleContent.value
    }).then(response => {
        console.log(response);
        router.go(-1);
    }).catch(error => {
        console.log(error);
    })
    console.log(articleContent)
}

// Article title model
const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    articleContent.value = null;
    articleTitle.value = null;
};






const toggleLink = () => {
    if (editor.isActive('link')) {
        editor.chain().focus().unsetLink().run();
    } else {
        const previousUrl = editor.getAttributes('link').href;
        const url = window.prompt('URL', previousUrl);

        // cancelled
        if (url === null) {
            return;
        }

        // empty
        if (url === '') {
            editor
                .chain()
                .focus()
                .extendMarkRange('link')
                .unsetLink()
                .run();

            return;
        }

        // update link
        editor
            .chain()
            .focus()
            .extendMarkRange('link')
            .setLink({ href: url })
            .run();

    }
}
const addImage = () => {
    const url = window.prompt('URL')

    if (url) {
        editor.chain().focus().setImage({ src: url }).run()
    }
}
onBeforeUnmount(() => {
    editor.destroy()
})

</script>


<style lang="scss" >
button.is-active {
    background-color: #fff !important;
    color: black !important;
}

div.cmds {
    position: fixed;
    z-index: 1;
    // box-shadow:  0px -20px 30px black;
    // width: 100rem;
    top: 3.6rem;
    width: calc(100% - 21%);

    // left: 19%;
}

div.cmds * {
    all: revert;
    // position: fixed;
}

div.cmds button {
    text-align: center;
    color: white;
    background-color: transparent;
    font-size: 1.2rem;
    border: none;
    border-radius: .5rem;
    padding: .5rem;
}

div.cmds button:hover {
    color: white;
    background-color: #303030;
    border: none;
}

div.cmds button i {
    font-style: initial;
}

div.cmds .divider {
    background-color: #ffffff40;
    height: 1.25rem;
    margin-left: 0.5rem;
    margin-right: 0.75rem;
    width: 1px;
    display: inline-block;
}



/* Basic editor styles */
::-webkit-scrollbar {
    width: .2rem;

}

::-webkit-scrollbar-track {
    background: white;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: black;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.ProseMirror {
    height: 80vh;
    outline: 1rem;
    padding: 3rem 2rem;

    >*+* {
        margin-top: 0.75em;
    }

    * {
        all: revert;
    }



    ul,
    ol {
        padding: 0 1rem;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        line-height: 1.1;
    }

    code {
        background-color: rgba(#616161, 0.1);
        color: #616161;
    }

    pre {
        background: #0D0D0D;
        color: #FFF;
        font-family: 'JetBrainsMono', monospace;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;

        code {
            color: inherit;
            padding: 0;
            background: none;
            font-size: 0.8rem;
        }
    }

    img {
        max-width: 100%;
        height: auto;

        &.ProseMirror-selectednode {
            outline: 3px solid #68CEF8;
        }
    }

    a {
        color: #68CEF8;
    }

    code {
        font-size: 0.9rem;
        padding: 0.25em;
        border-radius: 0.25em;
        background-color: rgba(#616161, 0.1);
        color: #616161;
        box-decoration-break: clone;
    }


    blockquote {
        padding-left: 1rem;
        border-left: 2px solid rgba(#0D0D0D, 0.1);
    }

    hr {
        border: none;
        border-top: 2px solid rgba(#0D0D0D, 0.1);
        margin: 2rem 0;
    }
}
</style>
