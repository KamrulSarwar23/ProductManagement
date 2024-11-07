<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import {useRouter, useRoute} from "vue-router"
import Swal from 'sweetalert2';

const router = useRouter()
const route = useRoute()
let products = ref([])
let links = ref([])
let searchQuery = ref('')
let errors = ref([]);

onMounted(async () => {
    getProducts();
})

watch(searchQuery, () => {
    getProducts();
})

const getProducts = async () => {
    let response = await axios.get('api/products?&searchQuery='+searchQuery.value)
    .then((response) => {
        products.value = response.data.products.data
        links.value = response.data.products.links
    })
    .catch((error) => {
      if (error.response.status === 422) {
        errors.value = error.response.data.errors;
      }
    });
}

const ourImage = (img) =>{
    return "/upload/"+img
}

const addProduct = () => {
    router.push('/products/create');
}

const editPage = (id) => {
    router.push('/products/'+id+'/edit')
}

const changePage = (link) => {
    if (!link.url || link.active) {
        return 
    }

    axios.get(link.url)
    .then((response) => {
        products.value = response.data.products.data
        links.value = response.data.products.links
    })
}

const deleteItem = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/products/${id}`)
            .then(() => {
                getProducts();
                toast.fire({ icon: "success", title: "Product Deleted successfully" });
            })
            .catch((error) => {
              if (error.response.status === 422) {
                errors.value = error.response.data.errors;
              }
            });
        }
    });
};

</script>
<template>


    <div class="products__list table  my-3">
        <div class="customers__titlebar dflex justify-content-between align-items-center">
            <div class="customers__titlebar--item">
                <h1 class="my-1">Products</h1>
            </div>
            <div class="customers__titlebar--item">
                <button @click="addProduct" class="btn btn-secondary my-1" >
                    Add Product
                </button>
            </div>
        </div>

        <div>
            <input style="padding:10px;" type="text" placeholder="Search Products..." v-model="searchQuery">
        </div>

        <div class="table--heading mt-2 products__list__heading " style="padding-top: 20px;background:#FFF">




            <!-- <p class="table--heading--col1">&#32;</p> -->
            <p class="table--heading--col1">image</p>
            <p class="table--heading--col2">
                Product
            </p>
            <p class="table--heading--col4"></p>
            <p class="table--heading--col3">
                Inventory
            </p>

            
            <!-- <p class="table--heading--col5">&#32;</p> -->
            <p class="table--heading--col5">actions</p>
        </div>

        <!-- product 1 -->
        <div  v-for="item in products" :key="item.id" class="table--items products__list__item" >
            <div class="products__list__item--imgWrapper">
                <img v-if="item.image" class="products__list__item--img" :src="ourImage(item.image)"  style="height: 40px;">
            </div>
            <a href="# " class="table--items--col2">
                {{ item.name }}
            </a>
            <p class="table--items--col2">
                {{ item.type }}
            </p>
            <p class="table--items--col3">
                {{ item.quantity }}
            </p>    
            
    
        <div>     

                <button @click="editPage(item.id)" class="btn-icon btn-icon-success" >
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="deleteItem(item.id)" class="btn-icon btn-icon-danger" >
                    <i class="far fa-trash-alt"></i>
                </button>

            </div>

        </div>


        <div class="table-paginate" style="background-color:white; padding:20px; text-align:center">
            <ul class="pagination" style="display:inline-block; padding-left:0; list-style:none; border-radius:4px;">
                <li v-for="(link, index) in links" :key="index" class="page-item" :class="{'active': link.active, 'disabled': !link.url}">
                    <a 
                        href="#" 
                        class="page-link" 
                        v-html="link.label" 
                        :style="{ 
                            color: link.active ? 'white' : '#007bff', 
                            backgroundColor: link.active ? '#007bff' : 'white', 
                            borderColor: link.active ? '#007bff' : '#dee2e6',
                            cursor: link.url ? 'pointer' : 'not-allowed'
                        }"
                        @click.prevent="changePage(link)"
                    ></a>
                </li>
            </ul>
        </div>
        


        
    </div>

</template>