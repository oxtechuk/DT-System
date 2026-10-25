import AsyncStorage from '@react-native-async-storage/async-storage';

// Base API configuration
// Note: When testing on physical device, replace 127.0.0.1 with your PC's local Wi-Fi IP (e.g. 192.168.1.50)
export const API_BASE_URL = 'http://127.0.0.1:8000/api/v1';

const TOKEN_KEY = '@ddt_auth_token';
const USER_KEY = '@ddt_auth_user';

export interface ApiResponse<T = any> {
  success: boolean;
  message?: string;
  data?: T;
  errors?: Record<string, string[]>;
}

export async function getAuthToken(): Promise<string | null> {
  try {
    return await AsyncStorage.getItem(TOKEN_KEY);
  } catch {
    return null;
  }
}

export async function setAuthToken(token: string): Promise<void> {
  await AsyncStorage.setItem(TOKEN_KEY, token);
}

export async function removeAuthToken(): Promise<void> {
  await AsyncStorage.removeItem(TOKEN_KEY);
  await AsyncStorage.removeItem(USER_KEY);
}

export async function getStoredUser(): Promise<any | null> {
  try {
    const raw = await AsyncStorage.getItem(USER_KEY);
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

export async function setStoredUser(user: any): Promise<void> {
  await AsyncStorage.setItem(USER_KEY, JSON.stringify(user));
}

export async function apiRequest<T = any>(
  endpoint: string,
  options: {
    method?: 'GET' | 'POST' | 'PUT' | 'DELETE';
    body?: any;
    headers?: Record<string, string>;
  } = {}
): Promise<ApiResponse<T>> {
  const token = await getAuthToken();

  const headers: Record<string, string> = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    ...(options.headers || {}),
  };

  const url = `${API_BASE_URL}${endpoint.startsWith('/') ? endpoint : `/${endpoint}`}`;

  try {
    const res = await fetch(url, {
      method: options.method || 'GET',
      headers,
      body: options.body ? JSON.stringify(options.body) : undefined,
    });

    const json = await res.json();
    return json as ApiResponse<T>;
  } catch (err: any) {
    return {
      success: false,
      message: err.message || 'تعذر الاتصال بسيرفر DDT، يرجى التأكد من اتصال الإنترنت.',
    };
  }
}
