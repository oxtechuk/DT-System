import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { useAuth } from '../context/AuthContext';

interface LoginScreenProps {
  onGoToRegister: () => void;
}

export const LoginScreen: React.FC<LoginScreenProps> = ({ onGoToRegister }) => {
  const { login } = useAuth();
  const [loginInput, setLoginInput] = useState('');
  const [password, setPassword] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);
  const [showPassword, setShowPassword] = useState(false);

  const handleLogin = async () => {
    if (!loginInput.trim() || !password.trim()) {
      setErrorMsg('يرجى إدخال رقم الهاتف أو البريد وكلمة المرور');
      return;
    }

    setErrorMsg(null);
    setIsSubmitting(true);
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Medium);

    const res = await login(loginInput.trim(), password);
    setIsSubmitting(false);

    if (!res.success) {
      setErrorMsg(res.message || 'بيانات الدخول غير صحيحة');
      Haptics.notificationAsync(Haptics.NotificationFeedbackType.Error);
    } else {
      Haptics.notificationAsync(Haptics.NotificationFeedbackType.Success);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <ScrollView contentContainerStyle={styles.scrollContent} keyboardShouldPersistTaps="handled">
        {/* Brand Header */}
        <View style={styles.brandBox}>
          <View style={styles.logoBadge}>
            <Text style={styles.logoText}>DDT</Text>
          </View>
          <Text style={styles.brandTitle}>DDT WORKING SPACE</Text>
          <Text style={styles.brandSubtitle}>أكثر من مكان.. مجتمع بيكبر معاك</Text>
        </View>

        {/* Card Form */}
        <View style={styles.formCard}>
          <Text style={styles.formHeading}>تسجيل الدخول</Text>
          <Text style={styles.formDesc}>
            أدخل رقم الهاتف أو البريد الإلكتروني للوصول إلى حسابك
          </Text>

          {errorMsg && (
            <View style={styles.errorBox}>
              <Ionicons name="alert-circle" size={16} color={colors.rose} />
              <Text style={styles.errorText}>{errorMsg}</Text>
            </View>
          )}

          {/* Login Input (Phone or Email) */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>رقم الهاتف أو البريد</Text>
            <View style={styles.inputWrapper}>
              <TextInput
                style={styles.input}
                placeholder="010XXXXXXXX أو example@ddt.space"
                placeholderTextColor={colors.gray}
                value={loginInput}
                onChangeText={setLoginInput}
                autoCapitalize="none"
                keyboardType="email-address"
                textAlign="right"
              />
              <Ionicons name="call-outline" size={18} color={colors.gray} style={styles.inputIcon} />
            </View>
          </View>

          {/* Password Input */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>كلمة المرور</Text>
            <View style={styles.inputWrapper}>
              <TouchableOpacity
                onPress={() => setShowPassword(!showPassword)}
                style={styles.eyeBtn}
              >
                <Ionicons
                  name={showPassword ? 'eye-off-outline' : 'eye-outline'}
                  size={18}
                  color={colors.gray}
                />
              </TouchableOpacity>
              <TextInput
                style={[styles.input, { paddingLeft: 40 }]}
                placeholder="••••••••"
                placeholderTextColor={colors.gray}
                value={password}
                onChangeText={setPassword}
                secureTextEntry={!showPassword}
                textAlign="right"
              />
              <Ionicons
                name="lock-closed-outline"
                size={18}
                color={colors.gray}
                style={styles.inputIcon}
              />
            </View>
          </View>

          {/* Submit Button */}
          <TouchableOpacity
            style={[styles.submitBtn, isSubmitting && styles.submitBtnDisabled]}
            onPress={handleLogin}
            disabled={isSubmitting}
            activeOpacity={0.85}
          >
            {isSubmitting ? (
              <ActivityIndicator color={colors.white} />
            ) : (
              <Text style={styles.submitBtnText}>دخول</Text>
            )}
          </TouchableOpacity>

          {/* Create Account link */}
          <View style={styles.registerRow}>
            <Text style={styles.registerPrompt}>ليس لديك حساب بعد؟ </Text>
            <TouchableOpacity onPress={onGoToRegister}>
              <Text style={styles.registerLink}>إنشاء حساب جديد</Text>
            </TouchableOpacity>
          </View>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.offWhite,
  },
  scrollContent: {
    padding: 24,
    justifyContent: 'center',
    minHeight: '100%',
  },
  brandBox: {
    alignItems: 'center',
    marginBottom: 28,
  },
  logoBadge: {
    width: 68,
    height: 68,
    borderRadius: 24,
    backgroundColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 12,
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.25,
    shadowRadius: 12,
    elevation: 6,
  },
  logoText: {
    color: colors.white,
    fontSize: 22,
    fontWeight: '900',
    letterSpacing: 1,
  },
  brandTitle: {
    fontSize: 18,
    fontWeight: '900',
    color: colors.charcoal,
    letterSpacing: 0.5,
  },
  brandSubtitle: {
    fontSize: 12,
    fontWeight: '600',
    color: colors.gray,
    marginTop: 4,
  },
  formCard: {
    backgroundColor: colors.white,
    borderRadius: 28,
    padding: 24,
    borderWidth: 1,
    borderColor: colors.border,
    shadowColor: colors.charcoal,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.05,
    shadowRadius: 14,
    elevation: 3,
  },
  formHeading: {
    fontSize: 18,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
  },
  formDesc: {
    fontSize: 12,
    color: colors.gray,
    textAlign: 'right',
    marginTop: 4,
    marginBottom: 16,
  },
  errorBox: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    backgroundColor: colors.roseLight,
    padding: 10,
    borderRadius: 14,
    marginBottom: 14,
    gap: 8,
  },
  errorText: {
    color: colors.rose,
    fontSize: 12,
    fontWeight: '700',
    flex: 1,
    textAlign: 'right',
  },
  inputGroup: {
    marginBottom: 16,
  },
  label: {
    fontSize: 12,
    fontWeight: '700',
    color: colors.charcoal,
    marginBottom: 6,
    textAlign: 'right',
  },
  inputWrapper: {
    position: 'relative',
    flexDirection: 'row',
    alignItems: 'center',
  },
  input: {
    flex: 1,
    height: 48,
    backgroundColor: colors.offWhite,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.border,
    paddingRight: 42,
    paddingLeft: 14,
    fontSize: 13,
    color: colors.charcoal,
    fontWeight: '600',
  },
  inputIcon: {
    position: 'absolute',
    right: 14,
  },
  eyeBtn: {
    position: 'absolute',
    left: 12,
    zIndex: 2,
    padding: 4,
  },
  submitBtn: {
    height: 48,
    backgroundColor: colors.primary,
    borderRadius: 24,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 8,
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 4,
  },
  submitBtnDisabled: {
    opacity: 0.6,
  },
  submitBtnText: {
    color: colors.white,
    fontSize: 14,
    fontWeight: '800',
  },
  registerRow: {
    flexDirection: 'row-reverse',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 20,
  },
  registerPrompt: {
    fontSize: 12,
    color: colors.gray,
  },
  registerLink: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.primary,
  },
});
