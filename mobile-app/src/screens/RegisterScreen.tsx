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

interface RegisterScreenProps {
  onGoToLogin: () => void;
}

export const RegisterScreen: React.FC<RegisterScreenProps> = ({ onGoToLogin }) => {
  const { register } = useAuth();
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [referralCode, setReferralCode] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  const handleRegister = async () => {
    if (!name.trim() || !phone.trim() || !password.trim()) {
      setErrorMsg('يرجى ملء الاسم، رقم الهاتف، وكلمة المرور');
      return;
    }

    if (password.length < 6) {
      setErrorMsg('كلمة المرور يجب أن تكون 6 أحرف أو أرقام على الأقل');
      return;
    }

    setErrorMsg(null);
    setIsSubmitting(true);
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Medium);

    const res = await register(
      name.trim(),
      phone.trim(),
      password,
      email.trim() || undefined,
      referralCode.trim() || undefined
    );
    setIsSubmitting(false);

    if (!res.success) {
      setErrorMsg(res.message || 'تعذر إنشاء الحساب، يرجى المحاولة لاحقاً');
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
          <Text style={styles.brandTitle}>عضوية جديدة في DDT</Text>
          <Text style={styles.brandSubtitle}>انضم لمجتمعنا واستمتع بمساحات العمل والكافيه</Text>
        </View>

        {/* Card Form */}
        <View style={styles.formCard}>
          <Text style={styles.formHeading}>إنشاء حساب جديد</Text>

          {errorMsg && (
            <View style={styles.errorBox}>
              <Ionicons name="alert-circle" size={16} color={colors.rose} />
              <Text style={styles.errorText}>{errorMsg}</Text>
            </View>
          )}

          {/* Full Name */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>الاسم بالكامل *</Text>
            <View style={styles.inputWrapper}>
              <TextInput
                style={styles.input}
                placeholder="مثال: أحمد محمد"
                placeholderTextColor={colors.gray}
                value={name}
                onChangeText={setName}
                textAlign="right"
              />
              <Ionicons name="person-outline" size={18} color={colors.gray} style={styles.inputIcon} />
            </View>
          </View>

          {/* Phone */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>رقم الهاتف *</Text>
            <View style={styles.inputWrapper}>
              <TextInput
                style={styles.input}
                placeholder="010XXXXXXXX"
                placeholderTextColor={colors.gray}
                value={phone}
                onChangeText={setPhone}
                keyboardType="phone-pad"
                textAlign="right"
              />
              <Ionicons name="call-outline" size={18} color={colors.gray} style={styles.inputIcon} />
            </View>
          </View>

          {/* Email (Optional) */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>البريد الإلكتروني (اختياري)</Text>
            <View style={styles.inputWrapper}>
              <TextInput
                style={styles.input}
                placeholder="example@mail.com"
                placeholderTextColor={colors.gray}
                value={email}
                onChangeText={setEmail}
                keyboardType="email-address"
                autoCapitalize="none"
                textAlign="right"
              />
              <Ionicons name="mail-outline" size={18} color={colors.gray} style={styles.inputIcon} />
            </View>
          </View>

          {/* Password */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>كلمة المرور *</Text>
            <View style={styles.inputWrapper}>
              <TextInput
                style={styles.input}
                placeholder="6 أحرف أو أرقام على الأقل"
                placeholderTextColor={colors.gray}
                value={password}
                onChangeText={setPassword}
                secureTextEntry
                textAlign="right"
              />
              <Ionicons name="lock-closed-outline" size={18} color={colors.gray} style={styles.inputIcon} />
            </View>
          </View>

          {/* Referral Code (Optional) */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>كود الإحالة / كود المدرس (اختياري)</Text>
            <View style={styles.inputWrapper}>
              <TextInput
                style={styles.input}
                placeholder="مثال: DDT_TEACHER"
                placeholderTextColor={colors.gray}
                value={referralCode}
                onChangeText={setReferralCode}
                autoCapitalize="characters"
                textAlign="right"
              />
              <Ionicons name="gift-outline" size={18} color={colors.gray} style={styles.inputIcon} />
            </View>
          </View>

          {/* Submit Button */}
          <TouchableOpacity
            style={[styles.submitBtn, isSubmitting && styles.submitBtnDisabled]}
            onPress={handleRegister}
            disabled={isSubmitting}
            activeOpacity={0.85}
          >
            {isSubmitting ? (
              <ActivityIndicator color={colors.white} />
            ) : (
              <Text style={styles.submitBtnText}>تأكيد وإنشاء الحساب</Text>
            )}
          </TouchableOpacity>

          {/* Back to Login */}
          <View style={styles.loginRow}>
            <Text style={styles.loginPrompt}>لديك حساب بالفعل؟ </Text>
            <TouchableOpacity onPress={onGoToLogin}>
              <Text style={styles.loginLink}>تسجيل الدخول</Text>
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
    paddingTop: 36,
  },
  brandBox: {
    alignItems: 'center',
    marginBottom: 20,
  },
  logoBadge: {
    width: 60,
    height: 60,
    borderRadius: 20,
    backgroundColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 10,
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.25,
    shadowRadius: 10,
    elevation: 5,
  },
  logoText: {
    color: colors.white,
    fontSize: 20,
    fontWeight: '900',
  },
  brandTitle: {
    fontSize: 18,
    fontWeight: '900',
    color: colors.charcoal,
  },
  brandSubtitle: {
    fontSize: 12,
    color: colors.gray,
    marginTop: 4,
    textAlign: 'center',
  },
  formCard: {
    backgroundColor: colors.white,
    borderRadius: 28,
    padding: 22,
    borderWidth: 1,
    borderColor: colors.border,
    shadowColor: colors.charcoal,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.05,
    shadowRadius: 14,
    elevation: 3,
    marginBottom: 20,
  },
  formHeading: {
    fontSize: 17,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
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
    marginBottom: 14,
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
    height: 46,
    backgroundColor: colors.offWhite,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: colors.border,
    paddingRight: 40,
    paddingLeft: 12,
    fontSize: 13,
    color: colors.charcoal,
    fontWeight: '600',
  },
  inputIcon: {
    position: 'absolute',
    right: 12,
  },
  submitBtn: {
    height: 48,
    backgroundColor: colors.primary,
    borderRadius: 24,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 10,
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
  loginRow: {
    flexDirection: 'row-reverse',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 18,
  },
  loginPrompt: {
    fontSize: 12,
    color: colors.gray,
  },
  loginLink: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.primary,
  },
});
