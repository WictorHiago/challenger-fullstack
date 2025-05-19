import api from './api';

interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role?: string;
}

interface LoginData {
  email: string;
  password: string;
}

interface AuthResponse {
  user: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
  token: string;
  message?: string;
}

export const authService = {
  /**
   * Registra um novo usuário
   * @param data Dados do usuário para registro
   */
  async register(data: RegisterData): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/register', data);
    
    // Armazenar token e dados do usuário no localStorage
    if (response.data.token) {
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
    }
    
    return response.data;
  },

  /**
   * Realiza login do usuário
   * @param data Credenciais de login
   */
  async login(data: LoginData): Promise<AuthResponse> {
    try {
      const response = await api.post<AuthResponse>('/login', data);
      
      // Armazenar token e dados do usuário no localStorage
      if (response.data.token) {
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      
      return response.data;
    } catch (error) {
      // Propagar o erro para ser tratado no componente
      console.error('Erro no serviço de login:', error);
      throw error;
    }
  },

  /**
   * Realiza logout do usuário
   */
  async logout(): Promise<void> {
    try {
      await api.post('/logout');
    } finally {
      // Mesmo que a requisição falhe, remover dados do localStorage
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  },

  /**
   * Obtém dados do usuário autenticado
   */
  async getUser() {
    const response = await api.get('/user');
    return response.data;
  },

  /**
   * Verifica se o usuário está autenticado
   */
  isAuthenticated(): boolean {
    return !!localStorage.getItem('token');
  },

  /**
   * Obtém o usuário atual do localStorage
   */
  getCurrentUser() {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      return JSON.parse(userStr);
    }
    return null;
  },

  /**
   * Verifica se o usuário tem a role especificada
   * @param role Role a ser verificada
   */
  hasRole(role: string): boolean {
    const user = this.getCurrentUser();
    return user && user.role === role;
  },

  /**
   * Verifica se o usuário é admin
   */
  isAdmin(): boolean {
    return this.hasRole('admin');
  }
};

export default authService;
