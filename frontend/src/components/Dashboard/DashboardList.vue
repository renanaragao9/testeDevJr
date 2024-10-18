<template>
  <div class="container-fluid mt-4">
    <div class="col s12">
      <h1 class="text-center custom-title">Dashboard</h1>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">
            <h4>Categorias</h4>
          </div>
          <ul class="list-group list-group-flush">
            <li
              v-for="(category, key) in statistics.categories"
              :key="key"
              @click="selectCategory(category)"
              class="list-group-item list-group-item-action category-item"
            >
            <button 
                @click.stop="generatePDF(category)" 
                class="btn btn-link" 
                title="Baixar PDF"
              >
                <i class="fa-solid fa-file-pdf" style="color: red;"></i>
              </button>
              <i 
                v-if="selectedCategory && selectedCategory.category_name === category.category_name" 
                class="fa-solid fa-check-circle" 
                style="color: green; margin-right: 8px;"
              ></i>
              {{ category.category_name }} ({{ category.products.length }} produtos)
              
            </li>
          </ul>
        </div>

        <div class="card mt-4">
          <div class="card-header">
            <h4>10 Produtos Mais Caros</h4>
          </div>
          <ul class="list-group list-group-flush">
            <li 
              v-for="(product, index) in topProducts" 
              :key="product.id" 
              class="list-group-item"
            >
              {{ index + 1 }}. {{ product.name }} - R$ {{ product.price }}
            </li>
          </ul>
        </div>
      </div>

      <div class="col-md-9">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h3>Total de Produtos por Categoria</h3>
                <canvas id="categoryChart"></canvas>
              </div>

              <div class="col-md-6">
                <h3>Categorias</h3>
                <canvas id="quantityChart"></canvas>
              </div>
            </div>

            <div v-if="selectedCategory" class="product-table border rounded p-4 bg-light mt-4">
              <h4>Produtos em: {{ selectedCategory.category_name }}</h4>

              <input
                type="text"
                v-model="searchQuery"
                placeholder="Pesquisar produtos..."
                class="form-control mb-3"
              />

              <table class="table table-hover" ref="productTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Nome do Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="product in filteredProducts" :key="product.id">
                    <td>{{ product.name }}</td>
                    <td>R$ {{ product.price }}</td>
                    <td>{{ product.qtd }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios       from 'axios';
import { Chart }   from 'chart.js';
import jsPDF       from 'jspdf';
import html2canvas from 'html2canvas';

export default {
  data() {
    return {
      
      statistics: {
        categories: {},
      },
      
      selectedCategory: null,
      searchQuery: '',
    };
  },

  computed: {
    filteredProducts() {
      if (!this.selectedCategory) {
        return [];
      }
      
      return this.selectedCategory.products.filter(product => {
        return product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
      });
    },
    
    topProducts() {
      const allProducts = Object.values(this.statistics.categories).flatMap(category => category.products);
      return allProducts.sort((a, b) => b.price - a.price).slice(0, 10);
    },
  },

  async created() {
    await this.fetchDashboardData();
  },

  methods: {
    async fetchDashboardData() {
      try {
        const token    = sessionStorage.getItem('authToken');
        
        const response = await axios.get('/api/dashboard', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.statistics = response.data.statistics;
        this.createCategoryChart();
        this.createQuantityChart();
      } catch (error) {
        console.error('Erro ao buscar dados do dashboard:', error);
      }
    },

    createCategoryChart() {
      const categoryNames = Object.values(this.statistics.categories).map(
        (category) => category.category_name
      );
      
      const productCounts = Object.values(this.statistics.categories).map(
        (category) => category.products.length
      );

      const ctx = document.getElementById('categoryChart').getContext('2d');
      
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: categoryNames,
          datasets: [
            {
              label: 'Quantidade de Produtos por Categoria',
              data: productCounts,
              backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
              ],
              borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
          },
        },
      });
    },
    
    createQuantityChart() {
      const categoryNames = Object.values(this.statistics.categories).map(
        (category) => category.category_name
      );
      
      const categoryCounts = Object.values(this.statistics.categories).map(
        (category) => category.products.length
      );

      const ctx = document.getElementById('quantityChart').getContext('2d');
      
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: categoryNames,
          datasets: [
            {
              label: 'Total de Categorias',
              data: categoryCounts,
              backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
              ],
              borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
          },
        },
      });
    },
    
    selectCategory(category) {
      this.selectedCategory = category;
      this.searchQuery = '';
    },

    async generatePDF(category) {
      const products = category.products;
      const pdf = new jsPDF();

      pdf.setFontSize(16);
      pdf.text(`Produtos em: ${category.category_name}`, 10, 10);
      pdf.setFontSize(12);

      let y = 20;
      pdf.text("Nome do Produto", 10, y);
      pdf.text("Preço", 70, y);
      pdf.text("Quantidade", 110, y);
      y += 10;

      products.forEach(product => {
        pdf.text(product.name, 10, y);
        pdf.text(`R$ ${product.price}`, 70, y);
        pdf.text(product.qtd.toString(), 110, y);
        y += 10;
      });

      pdf.save(`produtos_${category.category_name}.pdf`);
    },
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
  .container-fluid {
    background-color: #f5f5f5;
    padding: 20px;
  }

  .card {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .card-header {
    background-color: #333;
    color: #f8f9fa;
    font-weight: bold;
    padding: 10px;
    border-bottom: 2px solid #555;
  }

  .card-title {
    color: #333;
    margin-bottom: 20px;
  }

  .category-item {
    cursor: pointer;
    background-color: #f8f9fa;
    color: #333;
    transition: background-color 0.3s, color 0.3s;
  }

  .category-item:hover {
    background-color: #ddd;
    color: #000;
  }

  canvas {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .product-table {
    background-color: #ffffff;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-height: 400px;
    overflow-y: auto; 
  }

  .table-hover tbody tr:hover {
    background-color: #f1f1f1;
  }

  .thead-dark {
    background-color: #333;
    color: #f8f9fa;
  }
</style>
