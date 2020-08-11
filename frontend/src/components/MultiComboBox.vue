<template>
    <v-combobox
    :items="items"
    attach
    chips
    :label="label"
    :multiple="multiple"
    :value="value" 
    @change="onChange($event)"
    ></v-combobox>
</template>

<script>
    export default {
        name: "MultiComboBox",
        props:{
            items: Array,
            label: String,
            multiple: Boolean,
            value: Array
        },
        methods: {
            onChange(value) {
                if(!this.multiple)
                    value = [value];
                this.$emit('input', value);
            }
        }
    };
</script>