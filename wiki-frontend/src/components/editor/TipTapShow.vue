<template>
    <editor-content :editor="editor" class="h-[80vh] overflow-auto" />
</template>
  
<script setup>
import Link from '@tiptap/extension-link'
import Dropcursor from '@tiptap/extension-dropcursor'
import Image from '@tiptap/extension-image'
import Highlight from '@tiptap/extension-highlight'
import StarterKit from '@tiptap/starter-kit'
import { Editor, EditorContent } from '@tiptap/vue-3'
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import axios from 'axios'
import { useArticlesSotre } from '../../stores/articles-store'
import {useArticleStore} from '../../stores/article-store'
import { useRoute } from 'vue-router'
import { useParamsStore } from '../../stores/params-store'

axios.defaults.withCredentials = true;

const paramsStore = useParamsStore();
const articleStore = useArticleStore();

const route = useRoute();

const getArticle = onMounted(async() => {
    if (paramsStore.articleVersion) {
        await articleStore.getArticleByVersion(paramsStore.articleId, paramsStore.articleVersion);
        paramsStore.articleVersion = null;
        editor.commands.setContent(articleStore.content)
    }
    else {
        await articleStore.getArticle(paramsStore.articleId);
        editor.commands.setContent(articleStore.content)
    }
    editor.setEditable(false)
}
)
articleStore.clearArticle();
const editor = new Editor({
    extensions: [
        StarterKit,
        Highlight,
        Image,
    Dropcursor,
        Link
    ],
    // content: ``,
})

const submitArticle = async () => {
    const contentData = editor.getHTML()
    await axios.post('/api/articles', {
        space_id: 1,
        manual_id: 1,
        title: 'Atrticle 4',
        content: contentData
    }).then(response => {
        console.log(response);
    }).catch(error => {
        console.log(error);
    })
    console.log(contentData)
}

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
    // outline: 1rem;

    padding: 1rem !important;

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