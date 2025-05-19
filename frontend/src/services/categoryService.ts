import api from './api';

export interface Category {
  id: number;
  name: string;
  created_at?: string;
  updated_at?: string;
  products?: Array<{
    id: number;
    name: string;
    description: string;
    price: number;
    image_url: string;
  }>;
}

export interface CategoryFormData {
  name: string;
}

export const categoryService = {
  /**
   * Obtém lista de todas as categorias
   */
  async getCategories(): Promise<Category[]> {
    try {
      const response = await api.get('/categories');
      return response.data.categories || [];
    } catch (error) {
      console.error('Erro ao buscar categorias:', error);
      throw error;
    }
  },

  /**
   * Obtém uma categoria pelo ID
   * @param id ID da categoria
   */
  async getCategory(id: number): Promise<Category> {
    const response = await api.get(`/categories/${id}`);
    return response.data.category;
  },

  /**
   * Cria uma nova categoria (apenas admin)
   * @param data Dados da categoria
   */
  async createCategory(data: CategoryFormData): Promise<Category> {
    const response = await api.post('/categories', data);
    return response.data.category;
  },

  /**
   * Atualiza uma categoria existente (apenas admin)
   * @param id ID da categoria
   * @param data Dados atualizados da categoria
   */
  async updateCategory(id: number, data: CategoryFormData): Promise<Category> {
    const response = await api.put(`/categories/${id}`, data);
    return response.data.category;
  },

  /**
   * Remove uma categoria (apenas admin)
   * @param id ID da categoria
   */
  async deleteCategory(id: number): Promise<void> {
    await api.delete(`/categories/${id}`);
  }
};

export default categoryService;
