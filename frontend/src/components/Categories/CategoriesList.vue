<template>
  <div class="container">
    <div class="col s12">
      
      <h1 class="text-center" id="titulo" >Gerenciar Categorias</h1>
      
      <div class="text-center">
        <button class="btn btn-light" @click="openCreateModal">
          <i class="fa-solid fa-plus"></i> Adicionar Categoria
        </button>
      </div>
    </div>
    

    <!-- Exibir mensagem de sucesso ou erro -->
    <div v-if="message.content" :class="`alert ${message.type} alert-dismissible fade show`" role="alert">
      {{ message.content }}
      <button type="button" class="btn-close" @click="clearMessage" aria-label="Fechar"></button>
    </div>

    <!-- Card que contém a tabela, os botões e o campo de busca -->
    <div class="card shadow-lg border-0 rounded">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex justify-content-center flex-grow-1">
          <input
            v-model="searchTerm"
            @input="filterCategories"
            class="form-control form-control-lg"
            type="text"
            placeholder="Buscar Categoria..."
            style="border-radius: 20px;"
          />
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0" style="width: 100%;">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nome da Categoria</th>
                <th scope="col">Imagem</th>
                <th scope="col" class="text-center">Ações</th>
              </tr>
            </thead>
            <tbody class="text-center">
              <tr v-for="(category, index) in paginatedCategories" :key="category.id">
                <th scope="row">{{ index + 1 + (currentPage - 1) * itemsPerPage }}</th>
                <td>{{ category.name }}</td>
                <td>
                  <img
                    v-if="category.images && category.images.length > 0 && category.images[0].filename"
                    :src="'http://localhost:8000/storage/images/' + category.images[0].filename"
                    class="image-circle"
                    @click="openImageModal('http://localhost:8000/storage/images/' + category.images[0].filename)"
                    style="cursor: pointer;"
                    alt="Imagem da Categoria"
                  />
                  <span v-else>-</span>
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-warning me-2 rounded-pill" @click="openEditModal(category)">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <button class="btn btn-sm btn-danger rounded-pill" @click="openDeleteModal(category)">
                    <i class="bi bi-trash"></i> Excluir
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginação -->
        <nav class="mt-3">
          <ul class="pagination justify-content-center">
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
        </nav>
      </div>
    </div>

    <!-- Modal para exibir a imagem -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel">Imagem da Categoria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <img :src="modalImageUrl" class="img-fluid" alt="Imagem da Categoria" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Criação/Edição -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalLabel">{{ isEditing ? 'Editar Categoria' : 'Adicionar Categoria' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCategory">
              <div class="mb-3">
                <label for="categoryName" class="form-label">Nome da Categoria</label>
                <input type="text" class="form-control" id="categoryName" v-model="category.name" required style="border-radius: 10px;" />
              </div>
              <div class="mb-3">
                <label for="categoryImage" class="form-label">Imagem da Categoria</label>
                <input type="file" class="form-control" id="categoryImage" @change="handleImageUpload" />
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
            <p>Tem certeza que deseja excluir a categoria <strong>{{ categoryToDelete?.name }}</strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="confirmDelete">
              Excluir
              <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Modal } from 'bootstrap';
import api from '@/axios';

export default {
  data() {
    return {
      categories: [],
      filteredCategories: [],
      category: { name: '', image: null },
      categoryToDelete: null,
      modalImageUrl: '',
      searchTerm: '',
      isEditing: false,
      isLoading: false,
      currentPage: 1,
      itemsPerPage: 5,
      message: {
        content: '',
        type: '',
      },
      modalInstance: null,
      deleteModalInstance: null,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.filteredCategories.length / this.itemsPerPage);
    },
    
    paginatedCategories() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredCategories.slice(start, start + this.itemsPerPage);
    },
  },
  
  created() {
    this.fetchCategories();
  },

  methods: {
    async fetchCategories() {
      try {
        const token = sessionStorage.getItem('authToken');
        
        const response = await api.get('/categories', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        
        this.categories = response.data;
        
        this.filteredCategories = this.categories;
      } catch (error) {
        this.setMessage('Erro ao buscar categorias', 'alert-danger');
      }
    },
    
    openImageModal(imageUrl) {
      this.modalImageUrl = imageUrl;
      const imageModal = new Modal(document.getElementById('imageModal'));
      imageModal.show();
    },
   
    openCreateModal() {
      this.isEditing = false;
      this.category = { name: '' };
      this.modalInstance = new Modal(document.getElementById('categoryModal'));
      this.modalInstance.show();
    },
    
    openEditModal(category) {
      this.isEditing = true;
      this.category = { ...category };
      this.modalInstance = new Modal(document.getElementById('categoryModal'));
      this.modalInstance.show();
    },
    
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.category.image = file;
      }
    },
    
    async saveCategory() {
      
      const token = sessionStorage.getItem('authToken');
      
      try {
        const formData = new FormData();
        formData.append('name', this.category.name);

        if (this.category.image) {
          formData.append('image', this.category.image);
        }

        let url;
        if (this.isEditing) {
          
          url = `${api.defaults.baseURL}/categories/${this.category.id}`;

          await axios.put(url, formData, {
            headers: {
              'Authorization': `Bearer ${token}`,
            },
          });
          
          this.setMessage('Categoria atualizada com sucesso!', 'alert-success');
        
        } else {
          
          url = `${api.defaults.baseURL}/categories/`;
        
          await axios.post(url, formData, {
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'multipart/form-data', 
            },
          });
          
          this.setMessage('Categoria adicionada com sucesso!', 'alert-success');
        }

        this.fetchCategories();  // Recarrega as categorias
        
        this.modalInstance.hide();  // Fecha o modal
      
      } catch (error) {
        
        this.setMessage('Erro ao salvar categoria', 'alert-danger');
      
      }
    },
    
    openDeleteModal(category) {
      this.categoryToDelete = category;
      this.deleteModalInstance = new Modal(document.getElementById('deleteModal'));
      this.deleteModalInstance.show();
    },
    
    async confirmDelete() {
      
      const token = sessionStorage.getItem('authToken');
      
      this.isLoading = true;
      
      try {
        await axios.delete(`${api.defaults.baseURL}/categories/${this.categoryToDelete.id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        
        this.setMessage('Categoria excluída com sucesso!', 'alert-success');
        
        this.fetchCategories();
       
        this.deleteModalInstance.hide();
     
      } catch (error) {
        
        this.setMessage('Erro ao excluir categoria', 'alert-danger');
      
      } finally {
       
        this.isLoading = false;
      }
    },
    filterCategories() {
      
      const term = this.searchTerm.toLowerCase();
      
      this.filteredCategories = this.categories.filter(category =>
        category.name.toLowerCase().includes(term)
      );
      
      this.currentPage = 1;
    },
    
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    
    setMessage(content, type) {
      this.message = { content, type };
      
      setTimeout(() => {
        this.clearMessage();
      }, 5000);
    },
    
    clearMessage() {
      this.message = { content: '', type: '' };
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

body {
  font-family: 'Roboto', sans-serif;
}

h1 {
  font-size: 2.5rem;
  font-weight: 700;
  letter-spacing: 1px;
  color: #000;
  text-transform: uppercase;
  margin-bottom: 20px;
  font-family: 'Roboto', sans-serif;
}

.card {
  border-radius: 15px;
  border: none;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.card-header {
  background-color: #343a40;
  border-radius: 15px 15px 0 0;
}

.table {
  width: 100%;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-dark {
  background-color: #343a40;
  color: white;
}

.modal-header {
  background-color: #343a40;
  color: white;
}

.modal-footer .btn-dark {
  background-color: #343a40;
  border-color: #343a40;
}

.btn {
  border-radius: 25px;
  padding: 8px 20px;
}

.image-circle {
  width: 50px; 
  height: 50px;
  border-radius: 50%; 
  object-fit: cover; 
}

@media (max-width: 1024px) {
  h1 {
    font-size: 2rem;
  }

  .table-responsive {
    margin-bottom: 30px;
  }

  .pagination {
    font-size: 0.8rem;
  }

  .table {
    font-size: 0.9rem;
  }

  .card {
    margin: 10px;
  }
}

@media (max-width: 768px) {
  h1 {
    font-size: 1.8rem;
  }

  .card-header {
    flex-direction: column;
    align-items: center;
  }

  .form-control-lg {
    max-width: 100%;
  }

  .btn {
    margin-top: 10px;
    width: 100%;
    border-radius: 25px;
  }

  .table-responsive {
    margin-bottom: 30px;
    padding: 0 10px;
  }

  .table {
    font-size: 0.8rem;
  }

  .pagination {
    font-size: 0.7rem;
  }
}

@media (max-width: 480px) {
  h1 {
    font-size: 1.5rem;
  }

  .table-responsive {
    margin-bottom: 20px;
  }

  .pagination {
    font-size: 0.6rem;
  }

  .btn {
    font-size: 0.9rem;
    padding: 10px 15px;
    border-radius: 20px;
  }
}
</style>