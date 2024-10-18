<template>
  <div class="container py-4">
    <div class="col s12">
      <h1 class="text-center custom-title">Gerenciar Produtos</h1>

      <div class="text-center mb-4">
        <button class="btn btn-add-category" @click="openCreateModal">
          <i class="fa-solid fa-plus"></i> Adicionar Produto
        </button>
      </div>
    </div>

    <div v-if="message.content" :class="`alert ${message.type} alert-dismissible fade show`" role="alert">
      {{ message.content }}
      <button type="button" class="btn-close" @click="clearMessage" aria-label="Fechar"></button>
    </div>

    <div class="card shadow-lg border-0 rounded">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <input
          v-model="searchTerm"
          @input="filterProducts"
          class="form-control form-control-lg"
          type="text"
          placeholder="Buscar Produto..."
        />
      </div>
      
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Nome do Produto</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Qtd.</th>
                <th>Imagem</th>
                <th class="text-center">Ações</th>
              </tr>
            </thead>
            
            <tbody class="text-center">
              <tr v-for="(product, index) in paginatedProducts" :key="product.id">
                <th>{{ index + 1 + (currentPage - 1) * itemsPerPage }}</th>
                <td>{{ product.name }}</td>
                <td>{{ product.description }}</td>
                <td>{{ getCategoryName(product.category_id) }}</td>
                <td>{{ formatCurrency(product.price) }}</td>
                <td>{{ (product.qtd && product.qtd >= 0) ? product.qtd : 0 }}</td>
                <td>
                  <img
                    v-if="product.images && product.images.length > 0 && product.images[0].filename"
                    :src="'http://localhost:8000/storage/images/' + product.images[0].filename"
                    class="image-circle"
                    @click="openImageModal('http://localhost:8000/storage/images/' + product.images[0].filename, product.images[0].id)"
                    style="cursor: pointer;"
                    alt="Imagem do Produto"
                  />
                  <span v-else>-</span>
                </td>
                
                <td class="text-center">
                  <div class="action-buttons">
                    <button class="btn btn-edit me-2" @click="openEditModal(product)">
                      <i class="bi bi-pencil"></i> Editar
                    </button>
                    
                    <button class="btn btn-delete" @click="openDeleteModal(product)">
                      <i class="bi bi-trash"></i> Excluir
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginação -->
        <nav class="mt-3">
          <div class="pagination-container">
            <ul class="pagination justify-content-center flex-wrap">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="changePage(currentPage - 1)" aria-label="Anterior">
                  <span aria-hidden="true">&laquo;</span>
                </button>
              </li>
              
              <li class="page-item" :class="{ active: page === currentPage }" v-for="page in totalPages" :key="page">
                <button class="page-link" @click="changePage(page)">{{ page }}</button>
              </li>
              
              <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button class="page-link" @click="changePage(currentPage + 1)" aria-label="Próximo">
                  <span aria-hidden="true">&raquo;</span>
                </button>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>

    <!-- Modal para exibir a imagem -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel">Imagem do Produto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          
          <div class="modal-body text-center">
            <img :src="modalImageUrl" class="img-fluid" alt="Imagem do Produto" />
          </div>

          <div class="modal-footer">
            <button @click="downloadImage(imageId)" class="btn btn-primary">Baixar Imagem</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Criação/Edição -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalLabel">{{ isEditing ? 'Editar Produto' : 'Adicionar Produto' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          
          <div class="modal-body">
            <form @submit.prevent="saveProduct">
              <div class="mb-3">
                <label for="productName" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="productName" v-model="product.name" required />
              </div>
              
              <div class="mb-3">
                <label for="productDescription" class="form-label">Descrição</label>
                <textarea class="form-control" id="productDescription" v-model="product.description" required></textarea>
              </div>
              
              <div class="mb-3">
                <label for="productCategory" class="form-label">Categoria</label>
                <select class="form-select" id="productCategory" v-model="product.category_id" required>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
              </div>
              
              <div class="mb-3">
                <label for="productPrice" class="form-label">Preço</label>
                <input type="text" class="form-control" id="productPrice" v-model="product.price" required inputmode="decimal" />
              </div>
              
              <div class="mb-3">
                <label for="productQtd" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="productQtd" v-model="product.qtd" required inputmode="numeric" />
              </div>
              
              <div class="mb-3">
                <label for="productImage" class="form-label">Imagem do Produto</label>
                <input type="file" class="form-control" id="productImage" @change="handleProductImageUpload" />
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark">{{ isEditing ? 'Salvar Alterações' : 'Adicionar' }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          
          <div class="modal-body">
            <p>Tem certeza que deseja excluir o produto <strong>{{ productToDelete?.name }}</strong>?</p>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="confirmDelete">Excluir</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import axios      from 'axios';
  import { Modal }  from 'bootstrap';
  import api        from '@/axios';
  
  export default {
    data() {
      return {
        products: [],
        filteredProducts: [],
        categories: [],
        product: { name: '', description: '', category_id: null, price: '', qtd: '', image: null },
        modalImageUrl: '',
        productToDelete: null,
        searchTerm: '',
        isEditing: false,
        isLoading: false,
        currentPage: 1,
        itemsPerPage: 5,
        message: { content: '', type: '' },
        modalInstance: null,
        deleteModalInstance: null,
      };
    },
    
    computed: {
      totalPages() {
        return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
      },
      
      paginatedProducts() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredProducts.slice(start, start + this.itemsPerPage);
      },
    },
    
    created() {
      this.fetchProducts();
      this.fetchCategories(); // Carregar categorias para o dropdown
    },
   
    methods: {
      formatCurrency(value) {
        if (value) {
            return parseFloat(value).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }
        return 'R$ 0,00';  // Caso não haja valor
      },
      
      validatePrice(event) {
        event.target.value = event.target.value.replace(/[^0-9.,]/g, '');
        this.product.price = event.target.value;
      },

      validateQuantity(event) {
        event.target.value = event.target.value.replace(/[^0-9]/g, '');
        this.product.qtd   = event.target.value;
      },
      
      async fetchProducts() {
        
        const token = sessionStorage.getItem('authToken');
       
        try {
          const response = await api.get('/products', {
            headers: { Authorization: `Bearer ${token}` },
          });
         
          this.products         = response.data;
          this.filteredProducts = this.products;
       
        } catch (error) {
          this.setMessage('Erro ao buscar produtos', 'alert-danger');
        }
      },
      
      async fetchCategories() {
        
        const token = sessionStorage.getItem('authToken');
        
        try {
          const response = await api.get('/categories', {
            headers: { Authorization: `Bearer ${token}` },
          });
          
          this.categories = response.data;
        
        } catch (error) {
          this.setMessage('Erro ao buscar categorias', 'alert-danger');
        }
      },
     
      openImageModal(imageUrl, imageId) {
      // console.log(imageId)
        
        this.modalImageUrl = imageUrl;
        this.imageId       = imageId;
        const imageModal   = new Modal(document.getElementById('imageModal'));
        imageModal.show();
      },

      async downloadImage(imageId) {
        const token = sessionStorage.getItem('authToken');
        const url   = `${api.defaults.baseURL}/images/${imageId}/download`;

        try {
          const response = await axios.get(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            
            responseType: 'blob',
          });

          const link    = document.createElement('a');
          const blob    = new Blob([response.data], { type: response.headers['content-type'] });
          const urlBlob = window.URL.createObjectURL(blob);
          link.href     = urlBlob;

          link.setAttribute('download', `imagem_${imageId}.jpg`);
          document.body.appendChild(link);
          link.click();
          link.parentNode.removeChild(link); 
          window.URL.revokeObjectURL(urlBlob);
        
        } catch (error) {
            
          // console.error('Erro ao baixar a imagem:', error);
          
          this.setMessage('Erro ao baixar a imagem', 'alert-danger');
        }
      },
      
      openCreateModal() {
        this.isEditing      = false;
        this.product        = { name: '', description: '', category_id: null, price: '', qtd: '', image: null };
        this.modalInstance  = new Modal(document.getElementById('productModal'));
        this.modalInstance.show();
      },
      
      openEditModal(product) {
        this.isEditing     = true;
        this.product       = { ...product };
        this.modalInstance = new Modal(document.getElementById('productModal'));
        this.modalInstance.show();
      },
      
      openDeleteModal(product) {
        this.productToDelete     = product;
        this.deleteModalInstance = new Modal(document.getElementById('deleteModal'));
        this.deleteModalInstance.show();
      },
      
      handleProductImageUpload(event) {
        const file = event.target.files[0];
        
        if (file) {
            this.product.image = file;
        }
      },
      
      async saveProduct() {
        
        const token = sessionStorage.getItem('authToken');  
        
        try {
          const formData = new FormData();
          formData.append('name',        this.product.name);
          formData.append('description', this.product.description);
          formData.append('category_id', this.product.category_id);
          formData.append('price',       this.product.price);
          formData.append('qtd',         this.product.qtd);

          if (this.product.image) {
            formData.append('image',     this.product.image);
          }

          let url;
          
          if (this.isEditing) {
              
            url = `${api.defaults.baseURL}/products/${this.product.id}`;
              
            // Envia os dados com 'multipart/form-data'
            const response = await axios.put(url, formData, {
                headers: {
                  'Authorization': `Bearer ${token}`,
                },
            });
            
            this.setMessage('Produto atualizado com sucesso!', 'alert-success');
          } else {
      
              url =  `${api.defaults.baseURL}/products/`;
              
              const response = await axios.post(url, formData, {
                headers: {
                  'Authorization': `Bearer ${token}`,
                  'Content-Type': 'multipart/form-data',
                },
              });

              this.setMessage('Produto adicionado com sucesso!', 'alert-success');
          }

          this.fetchProducts();
          this.modalInstance.hide();

        } catch (error) {
          this.setMessage('Erro ao salvar produto', 'alert-danger');
        }
      },

      async confirmDelete() {
        
        const token    = sessionStorage.getItem('authToken');
        this.isLoading = true;
        
        try {
          await axios.delete(`${api.defaults.baseURL}/products/${this.productToDelete.id}`, {
            headers: { Authorization: `Bearer ${token}` },
          });
          
          this.setMessage('Produto excluído com sucesso', 'alert-success');
          
          this.fetchProducts();
          
          this.deleteModalInstance.hide();
       
        } catch (error) {
          this.setMessage('Erro ao excluir produto', 'alert-danger');
       
        } finally {
          this.isLoading = false;
        }
      },
      
      setMessage(content, type) {
        this.message = { content, type };
      },
      
      clearMessage() {
        this.message.content = '';
      },
      
      filterProducts() {
        if (this.searchTerm) {
          this.filteredProducts = this.products.filter(
            (product) =>
            product.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
            product.description.toLowerCase().includes(this.searchTerm.toLowerCase())
          );
        
        } else {
          this.filteredProducts = this.products;
        }
      },
     
      getCategoryName(id) {
        const category = this.categories.find((category) => category.id === id);
        return category ? category.name : 'Desconhecida';
      },
      
      changePage(page) {
        if (page > 0 && page <= this.totalPages) {
          this.currentPage = page;
        }
      }
    },
  };
  </script>

<style scoped>

  .custom-title {
    color: #333;
    border-bottom: 2px solid #eaeaea; 
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 55px;
  }

  h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #000;
  }

  .btn-add-category {
    background-color: #343a40;
    color: #fff;
    border-radius: 30px;
    padding: 10px 20px;
  }

  .btn-add-category:hover {
    background-color: #000;
  }

  .card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .card-header {
    background-color: #343a40;
    color: #fff;
  }

  .table {
    width: 100%;
  }

  .table-bordered {
    border: 1px solid #dee2e6;
  }

  .table-dark {
    background-color: #000;
  }

  .image-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
  }

  .action-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
  }

  .btn-edit {
    background-color: #01579b;
    color: #fff;
    border-radius: 20px;
  }

  .btn-delete {
    background-color: #e65100;
    color: #fff;
    border-radius: 20px;
  }

  .btn-edit, .btn-delete {
    margin: 5px;
  }

  .pagination-container {
    overflow-x: auto; 
    white-space: nowrap; 
    padding: 10px 0; 
  }

  .page-link {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .pagination .page-item.disabled .page-link {
    cursor: not-allowed;
    opacity: 0.5;
  }

  .pagination .page-item.active .page-link {
    background-color: #343a40;
    border-color: #343a40;
    color: white;
  }

  .modal-header.bg-dark {
    background-color: #343a40;
  }

  .modal-header.bg-danger {
    background-color: #dc3545;
  }

  @media (max-width: 768px) {
    
    .custom-title {
      font-size: 28px;
    }
    
    .pagination {
      flex-direction: row;
      overflow-x: auto;
      padding: 10px 0;
    }

    .page-link {
      font-size: 14px;
      width: 35px;
      height: 35px;
    }

    .action-buttons {
      flex-direction: column;
      width: 100%;
    }

    .btn-edit, .btn-delete {
      width: 100%;
    }
  }
</style>