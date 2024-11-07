
<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { useRouter, useRoute } from "vue-router";

const formData = reactive({
  name: "",
  description: "",
  image: "",
  type: "",
  quantity: "",
  price: "",
});

const router = useRouter();
const route = useRoute();
let errors = ref([]);


const getImage = () => {
  let image = "/upload/no-image.jpg";

  if (formData.image) {
    if (formData.image.indexOf("base64") != -1) {
      image = formData.image;
    } else {
        image = `/upload/${formData.image}`;
    }
  }
  return image;
};


const handleFileChange = (e) => {
  let file = e.target.files[0];
  let reader = new FileReader();
  reader.onloadend = (file) => {
    formData.image = reader.result;
  };

  reader.readAsDataURL(file);
};


const editMode = ref(false);

onMounted(() => {
  if (route.name === "products.edit") {
    editMode.value = true;
    getProduct();
  }
});


const getProduct = async () => {
  let response = await axios.get(`/api/products/${route.params.id}/edit`)
    .then((response) => {
      formData.name = response.data.product.name;
      formData.description = response.data.product.description;
      formData.type = response.data.product.type;
      formData.quantity = response.data.product.quantity;
      formData.price = response.data.product.price;
      formData.image = response.data.product.image;
    });
};

const handleSave = (values, actions) => {

  if (editMode.value) {
    UpdateProduct(values, actions);
  } else {
    CreateProduct(values, actions);
  }

};

const CreateProduct = (values, actions) => {
  axios.post("/api/products", formData)

    .then((response) => {
      router.push("/");

      toast.fire({ icon: "success", title: "Product Added successfully" });
    })
    .catch((error) => {
      if (error.response.status === 422) {
        errors.value = error.response.data.errors;
      }
    });
};


const UpdateProduct = (values, actions) => {

  axios.put(`/api/products/${route.params.id}`, formData)

    .then((response) => {
      router.push("/");

      toast.fire({ icon: "success", title: "Product Updated successfully" });
    })
    .catch((error) => {
      if (error.response.status === 422) {
        errors.value = error.response.data.errors;
      }
    });
};





</script>

<template>
  <div class="products__create">
    <div
      class="products__create__titlebar dflex justify-content-between align-items-center"
    >
      <div class="products__create__titlebar--item">
        <h1 v-if="editMode" class="my-1">Edit Product</h1>
        <h1 v-else class="my-1">Add Product</h1>
      </div>
      <div class="products__create__titlebar--item">
        <button @click="handleSave" class="btn btn-secondary ml-1">Save</button>
      </div>
    </div>

    <div class="products__create__cardWrapper mt-2">
      <div class="products__create__main">
        <div class="products__create__main--addInfo card py-2 px-2 bg-white">
          <p class="mb-1">Name</p>
          <input v-model="formData.name" type="text" class="input" />
          <small style="color: red" v-if="errors.name">{{ errors.name }}</small>
          <p class="my-1">Description (optional)</p>
          <textarea
            v-model="formData.description"
            cols="10"
            rows="5"
            class="textarea"
          ></textarea>
          <small style="color: red" v-if="errors.description">{{
            errors.description
          }}</small>
          <div class="products__create__main--media--images mt-2">
            <ul
              class="products__create__main--media--images--list list-unstyled"
            >
              <li class="products__create__main--media--images--item">
                <div
                  class="products__create__main--media--images--item--imgWrapper"
                >
                  <img
                    class="products__create__main--media--images--item--img"
                    alt=""
                    :src="getImage()"
                  />
                </div>
              </li>
              <!-- upload image small -->
              <li class="products__create__main--media--images--item">
                <form class="products__create__main--media--images--item--form">
                  <label
                    class="products__create__main--media--images--item--form--label"
                    for="myfile"
                    >Add Image</label
                  >
                  <input
                    @change="handleFileChange"
                    class="products__create__main--media--images--item--form--input"
                    type="file"
                    id="myfile"
                  />
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="products__create__sidebar">
        <!-- Product Organization -->
        <div class="card py-2 px-2 bg-white">
          <!-- Product unit -->
          <div class="my-3">
            <p>Product type</p>
            <input v-model="formData.type" type="text" class="input" />
          </div>
          <hr />

          <!-- Product invrntory -->
          <div class="my-3">
            <p>Inventory</p>
            <input v-model="formData.quantity" type="text" class="input" />
            <small style="color: red" v-if="errors.quantity">{{
              errors.quantity
            }}</small>
          </div>
          <hr />

          <!-- Product Price -->
          <div class="my-3">
            <p>Price</p>
            <input v-model="formData.price" type="text" class="input" />
            <small style="color: red" v-if="errors.price">{{
              errors.price
            }}</small>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer Bar -->
    <div class="dflex justify-content-between align-items-center my-3">
      <p></p>
      <button @click="handleSave" class="btn btn-secondary">Save</button>
    </div>
  </div>
</template>