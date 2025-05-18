import api from './api';

export interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  validity_date: string;
  image: string;
  image_url: string;
  category_id: number;
  created_at?: string;
  updated_at?: string;
  category?: {
    id: number;
    name: string;
  };
}

export interface ProductFormData {
  name: string;
  description: string;
  price: number;
  validity_date: string;
  image_url: string;
  category_id: number;
}

export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: Array<{
    url: string | null;
    label: string;
    active: boolean;
  }>;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export const productService = {
  /**
   * Obtém lista paginada de produtos
   * @param page Número da página
   * @param search Termo de busca opcional
   */
  async getProducts(page = 1, search = ''): Promise<PaginatedResponse<Product>> {
    const params = { page };
    if (search) {
      Object.assign(params, { search });
    }
    
    const response = await api.get('/products', { params });
    return response.data;
  },

  /**
   * Busca produtos por termo
   * @param term Termo de busca
   */
  async searchProducts(term: string): Promise<PaginatedResponse<Product>> {
    const response = await api.get(`/products/search/${term}`);
    return response.data;
  },

  /**
   * Obtém um produto pelo ID
   * @param id ID do produto
   */
  async getProduct(id: number): Promise<Product> {
    const response = await api.get(`/products/${id}`);
    return response.data;
  },

  /**
   * Cria um novo produto
   * @param data Dados do produto
   */
  async createProduct(data: ProductFormData): Promise<Product> {
    const response = await api.post('/products', data);
    return response.data;
  },

  /**
   * Atualiza um produto existente
   * @param id ID do produto
   * @param data Dados atualizados do produto
   */
  async updateProduct(id: number, data: Partial<ProductFormData>): Promise<Product> {
    const response = await api.put(`/products/${id}`, data);
    return response.data;
  },

  /**
   * Remove um produto
   * @param id ID do produto
   */
  async deleteProduct(id: number): Promise<void> {
    await api.delete(`/products/${id}`);
  }
};

export default productService;
