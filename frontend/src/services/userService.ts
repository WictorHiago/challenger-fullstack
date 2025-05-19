import api from './api';

export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  created_at?: string;
  updated_at?: string;
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

export const userService = {
  /**
   * Obtém lista de usuários (apenas para administradores)
   */
  async getUsers(): Promise<User[]> {
    try {
      const response = await api.get('/users');
      console.log('Resposta da API de usuários:', response.data);
      
      // Verificar diferentes formatos possíveis da resposta
      if (response.data.data) {
        // Formato padrão do Laravel Resource Collection
        return response.data.data;
      } else if (response.data.users) {
        // Formato personalizado com campo 'users'
        return response.data.users;
      } else if (Array.isArray(response.data)) {
        // Array direto de usuários
        return response.data;
      } else {
        // Fallback para array vazio
        console.error('Formato de resposta não reconhecido:', response.data);
        return [];
      }
    } catch (error) {
      console.error('Erro ao buscar usuários:', error);
      throw error;
    }
  },

  /**
   * Obtém um usuário pelo ID
   * @param id ID do usuário
   */
  async getUser(id: number): Promise<User> {
    const response = await api.get(`/users/${id}`);
    return response.data.user || response.data;
  }
};

export default userService;
