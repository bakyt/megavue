<template>
    <ul v-if="node.children || node.categories">
        <li class="hassubs" v-for="children in node.children" v-bind:key="children.id">
            <a href="javascript:void(0)" :title="children.name">{{ children.name }}<i class="fas fa-chevron-right"></i></a>
            <node v-if="children" :node="children" ></node>
        </li>
        <li class="sub-category" v-for="category in node.categories" v-bind:key="category.id">
            <router-link :to="{ name:'categories', params:{ id:category.id } }" :title="category.name">{{ category.name }}</router-link>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "node",
        props: {
            node: Object
        }
    };
</script>
