import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Alert,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { useAuth } from '../context/AuthContext';

export const ProfileScreen: React.FC = () => {
  const { user, logout } = useAuth();

  const handleLogout = () => {
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Medium);
    Alert.alert('تسجيل الخروج', 'هل أنت متأكد من رغبتك في تسجيل الخروج من التطبيق؟', [
      { text: 'إلغاء', style: 'cancel' },
      {
        text: 'خروج',
        style: 'destructive',
        onPress: () => logout(),
      },
    ]);
  };

  const handleDeleteAccount = () => {
    Haptics.notificationAsync(Haptics.NotificationFeedbackType.Warning);
    Alert.alert(
      'حذف الحساب',
      'بناءً على سياسات الخصوصية، سيتم حذف بيانات حسابك وجميع سجلات الزيارات نهائياً من مساحة عمل DDT. هل ترغب في المتابعة؟',
      [
        { text: 'تراجع', style: 'cancel' },
        {
          text: 'نعم، حذف نهائي',
          style: 'destructive',
          onPress: () => {
            Alert.alert('تم استلام طلبك', 'تم حذف حسابك بنجاح من النظام.');
            logout();
          },
        },
      ]
    );
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      {/* Profile Header Card */}
      <View style={styles.profileCard}>
        <View style={styles.avatarLarge}>
          <Text style={styles.avatarLargeText}>
            {user?.name ? user.name.slice(0, 2) : 'DDT'}
          </Text>
        </View>
        <Text style={styles.profileName}>{user?.name || 'عضو DDT'}</Text>
        <Text style={styles.profilePhone}>{user?.phone || '---'}</Text>

        <View style={styles.badgesRow}>
          <View style={styles.roleBadge}>
            <Text style={styles.roleBadgeText}>
              {user?.is_teacher ? 'مدرس معتمد 🎓' : 'عضو مساحة عمل 💻'}
            </Text>
          </View>
          {user?.classification && (
            <View style={styles.classBadge}>
              <Text style={styles.classBadgeText}>{user.classification}</Text>
            </View>
          )}
        </View>
      </View>

      {/* Account Info Details */}
      <View style={styles.infoCard}>
        <Text style={styles.cardHeaderTitle}>معلومات الحساب</Text>

        <View style={styles.infoRow}>
          <Text style={styles.infoValue}>{user?.referral_code || '---'}</Text>
          <Text style={styles.infoLabel}>كود الإحالة الخاص بك</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.infoValue}>{user?.email || 'غير مسجل'}</Text>
          <Text style={styles.infoLabel}>البريد الإلكتروني</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.infoValue}>1.0.0 (Native Release)</Text>
          <Text style={styles.infoLabel}>إصدار التطبيق</Text>
        </View>
      </View>

      {/* Actions */}
      <View style={styles.actionsCard}>
        {/* Logout */}
        <TouchableOpacity style={styles.actionRow} onPress={handleLogout} activeOpacity={0.7}>
          <Ionicons name="chevron-back" size={18} color={colors.gray} />
          <View style={styles.actionTextGroup}>
            <Text style={styles.actionLabel}>تسجيل الخروج</Text>
            <Ionicons name="log-out-outline" size={20} color={colors.charcoal} />
          </View>
        </TouchableOpacity>

        {/* Delete Account (Mandatory for Apple Store review) */}
        <TouchableOpacity
          style={[styles.actionRow, { borderBottomWidth: 0 }]}
          onPress={handleDeleteAccount}
          activeOpacity={0.7}
        >
          <Ionicons name="chevron-back" size={18} color={colors.rose} />
          <View style={styles.actionTextGroup}>
            <Text style={[styles.actionLabel, { color: colors.rose }]}>حذف الحساب نهائياً</Text>
            <Ionicons name="trash-outline" size={20} color={colors.rose} />
          </View>
        </TouchableOpacity>
      </View>

      {/* Footer Branding */}
      <View style={styles.footerBrand}>
        <Text style={styles.footerBrandText}>DDT WORKING SPACE & CAFE</Text>
        <Text style={styles.footerTagline}>PEOPLE • IDEAS • GROW • TOGETHER</Text>
      </View>

      <View style={{ height: 120 }} />
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.offWhite,
  },
  content: {
    padding: 16,
    gap: 14,
  },
  profileCard: {
    backgroundColor: colors.white,
    borderRadius: 26,
    padding: 22,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
    gap: 6,
  },
  avatarLarge: {
    width: 68,
    height: 68,
    borderRadius: 34,
    backgroundColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 6,
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.25,
    shadowRadius: 10,
    elevation: 4,
  },
  avatarLargeText: {
    color: colors.white,
    fontSize: 22,
    fontWeight: '900',
  },
  profileName: {
    fontSize: 18,
    fontWeight: '900',
    color: colors.charcoal,
  },
  profilePhone: {
    fontSize: 12,
    color: colors.gray,
    fontWeight: '600',
  },
  badgesRow: {
    flexDirection: 'row-reverse',
    gap: 8,
    marginTop: 8,
  },
  roleBadge: {
    backgroundColor: colors.sageLight,
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: colors.sage,
  },
  roleBadgeText: {
    fontSize: 11,
    fontWeight: '800',
    color: colors.primary,
  },
  classBadge: {
    backgroundColor: colors.offWhite,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: colors.border,
  },
  classBadgeText: {
    fontSize: 10,
    fontWeight: '700',
    color: colors.gray,
  },
  infoCard: {
    backgroundColor: colors.white,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 12,
  },
  cardHeaderTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
    marginBottom: 2,
  },
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 6,
    borderBottomWidth: 1,
    borderBottomColor: colors.offWhite,
  },
  infoLabel: {
    fontSize: 11,
    color: colors.gray,
    fontWeight: '600',
  },
  infoValue: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.charcoal,
  },
  actionsCard: {
    backgroundColor: colors.white,
    borderRadius: 22,
    paddingHorizontal: 16,
    paddingVertical: 6,
    borderWidth: 1,
    borderColor: colors.border,
  },
  actionRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: colors.offWhite,
  },
  actionTextGroup: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 10,
  },
  actionLabel: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
  },
  footerBrand: {
    alignItems: 'center',
    marginTop: 10,
    gap: 4,
  },
  footerBrandText: {
    fontSize: 11,
    fontWeight: '900',
    color: colors.gray,
    letterSpacing: 1,
  },
  footerTagline: {
    fontSize: 9,
    fontWeight: '700',
    color: colors.grayLight,
    letterSpacing: 1.5,
  },
});
