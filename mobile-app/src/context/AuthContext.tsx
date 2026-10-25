import React, { createContext, useContext, useState, useEffect } from 'react';
import {
  apiRequest,
  getAuthToken,
  setAuthToken,
  removeAuthToken,
  getStoredUser,
  setStoredUser,
} from '../config/api';

export interface CustomerUser {
  id: number;
  name: string;
  phone: string;
  email?: string;
  referral_code?: string;
  classification?: string;
  is_teacher: boolean;
  teacher_code?: string;
  loyalty?: {
    current_visits: number;
    completed_cycles: number;
    target_visits: number;
    has_unclaimed_free_drink: boolean;
  };
}

interface AuthContextType {
  user: CustomerUser | null;
  token: string | null;
  isLoading: boolean;
  login: (login: string, password: string) => Promise<{ success: boolean; message?: string }>;
  register: (
    name: string,
    phone: string,
    password: string,
    email?: string,
    referralCode?: string
  ) => Promise<{ success: boolean; message?: string }>;
  logout: () => Promise<void>;
  refreshProfile: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<CustomerUser | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    loadStoredSession();
  }, []);

  const loadStoredSession = async () => {
    try {
      const storedToken = await getAuthToken();
      const storedUser = await getStoredUser();

      if (storedToken && storedUser) {
        setToken(storedToken);
        setUser(storedUser);
        // Refresh silently in background
        fetchProfile();
      }
    } finally {
      setIsLoading(false);
    }
  };

  const fetchProfile = async () => {
    const res = await apiRequest('/auth/me');
    if (res.success && res.data?.customer) {
      setUser(res.data.customer);
      await setStoredUser(res.data.customer);
    }
  };

  const login = async (loginVal: string, passwordVal: string) => {
    const res = await apiRequest('/auth/login', {
      method: 'POST',
      body: {
        login: loginVal,
        password: passwordVal,
        device: 'ddt-mobile-native',
      },
    });

    if (res.success && res.data?.token && res.data?.customer) {
      setToken(res.data.token);
      setUser(res.data.customer);
      await setAuthToken(res.data.token);
      await setStoredUser(res.data.customer);
      return { success: true };
    }

    return {
      success: false,
      message: res.message || 'بيانات الدخول غير صحيحة',
    };
  };

  const register = async (
    name: string,
    phone: string,
    password: string,
    email?: string,
    referralCode?: string
  ) => {
    const res = await apiRequest('/auth/register', {
      method: 'POST',
      body: {
        name,
        phone,
        password,
        email: email || undefined,
        referral_code: referralCode || undefined,
        device: 'ddt-mobile-native',
      },
    });

    if (res.success && res.data?.token && res.data?.customer) {
      setToken(res.data.token);
      setUser(res.data.customer);
      await setAuthToken(res.data.token);
      await setStoredUser(res.data.customer);
      return { success: true };
    }

    return {
      success: false,
      message: res.message || 'تعذر إنشاء الحساب',
    };
  };

  const logout = async () => {
    try {
      await apiRequest('/auth/logout', { method: 'POST' });
    } catch {
      // Ignore network errors on logout
    }
    setToken(null);
    setUser(null);
    await removeAuthToken();
  };

  const refreshProfile = async () => {
    await fetchProfile();
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        isLoading,
        login,
        register,
        logout,
        refreshProfile,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
