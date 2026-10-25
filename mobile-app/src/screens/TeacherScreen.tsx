import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
  ActivityIndicator,
  Share,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { apiRequest } from '../config/api';

export const TeacherScreen: React.FC = () => {
  const [data, setData] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchTeacherData();
  }, []);

  const fetchTeacherData = async () => {
    setIsLoading(true);
    const res = await apiRequest('/portal/teacher');
    if (res.success && res.data) {
      setData(res.data);
    }
    setIsLoading(false);
  };

  const onRefresh = async () => {
    setRefreshing(true);
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
    await fetchTeacherData();
    setRefreshing(false);
  };

  const handleShare = async () => {
    if (!data?.stats?.referral_link) return;
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Medium);
    try {
      await Share.share({
        message: `سجل في مساحة عمل DDT عبر الرابط الخاص بي واحصل على مميزات حصرية: ${data.stats.referral_link}`,
        title: 'دعوة للانضمام إلى DDT Working Space',
      });
    } catch {
      // Ignore
    }
  };

  if (isLoading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>جارٍ تحميل لوحة أرباح المدرس...</Text>
      </View>
    );
  }

  const stats = data?.stats || {};
  const visits = data?.visits?.data || [];

  return (
    <ScrollView
      style={styles.container}
      contentContainerStyle={styles.content}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} />
      }
    >
      {/* Teacher Header Banner */}
      <View style={styles.headerCard}>
        <View style={styles.headerIcon}>
          <Ionicons name="school" size={24} color={colors.white} />
        </View>
        <View style={styles.headerTextGroup}>
          <Text style={styles.headerTitle}>بوابة أرباح المدرس المعتمد</Text>
          <Text style={styles.headerSubtitle}>
            كود الإحالة: <Text style={styles.codeHighlight}>{stats.referral_code || '---'}</Text>
          </Text>
        </View>
      </View>

      {/* Earnings Summary Cards */}
      <View style={styles.earningsGrid}>
        {/* Total Earnings */}
        <View style={styles.earningBox}>
          <Text style={styles.earningLabel}>إجمالي الأرباح</Text>
          <Text style={styles.earningValue}>
            {(stats.total_earnings || 0).toFixed(2)}{' '}
            <Text style={styles.currency}>ج.م</Text>
          </Text>
        </View>

        {/* Pending Commission */}
        <View style={styles.earningBox}>
          <Text style={styles.earningLabel}>أرباح معلقة</Text>
          <Text style={[styles.earningValue, { color: colors.amber }]}>
            {(stats.pending_commission || 0).toFixed(2)}{' '}
            <Text style={styles.currency}>ج.م</Text>
          </Text>
        </View>
      </View>

      {/* Students Counters */}
      <View style={styles.statsRow}>
        <View style={styles.smallStatCard}>
          <Ionicons name="people-outline" size={18} color={colors.primary} />
          <Text style={styles.smallStatNum}>{stats.total_students || 0}</Text>
          <Text style={styles.smallStatLabel}>إجمالي الطلاب</Text>
        </View>
        <View style={styles.smallStatCard}>
          <Ionicons name="calendar-outline" size={18} color={colors.primary} />
          <Text style={styles.smallStatNum}>{stats.month_students || 0}</Text>
          <Text style={styles.smallStatLabel}>طلاب الشهر</Text>
        </View>
        <View style={styles.smallStatCard}>
          <Ionicons name="today-outline" size={18} color={colors.primary} />
          <Text style={styles.smallStatNum}>{stats.today_students || 0}</Text>
          <Text style={styles.smallStatLabel}>طلاب اليوم</Text>
        </View>
      </View>

      {/* Share Referral Link Card */}
      <View style={styles.referralCard}>
        <View style={styles.referralHeader}>
          <Ionicons name="share-social-outline" size={20} color={colors.primary} />
          <Text style={styles.referralTitle}>رابط وكود دعوة الطلاب</Text>
        </View>
        <Text style={styles.referralDesc}>
          شارك هذا الرابط مع طلابك؛ عند زيارتهم للمساحة يتم تسجيل العمولة تلقائياً في حسابك.
        </Text>

        <TouchableOpacity style={styles.shareBtn} onPress={handleShare} activeOpacity={0.85}>
          <Ionicons name="share-outline" size={18} color={colors.white} />
          <Text style={styles.shareBtnText}>مشاركة الرابط مع الطلاب</Text>
        </TouchableOpacity>
      </View>

      {/* Student Visits Table */}
      <View style={styles.visitsSection}>
        <Text style={styles.visitsSectionTitle}>سجل زيارات الطلاب والعمولات</Text>

        {visits.map((item: any) => (
          <View key={item.id} style={styles.visitRow}>
            <View style={styles.visitEarnings}>
              <Text style={styles.commissionText}>+{item.commission_amount.toFixed(2)} ج.م</Text>
              <Text style={styles.visitDate}>{item.visited_at}</Text>
            </View>
            <View style={styles.visitInfo}>
              <Text style={styles.studentName}>{item.student_name}</Text>
              <Text style={styles.studentPhone}>{item.student_phone}</Text>
            </View>
          </View>
        ))}

        {visits.length === 0 && (
          <View style={styles.emptyVisits}>
            <Ionicons name="person-outline" size={28} color={colors.gray} />
            <Text style={styles.emptyVisitsText}>لا توجد زيارات مسجلة لطلابك حتى الآن</Text>
          </View>
        )}
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
  centerContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: colors.offWhite,
    gap: 10,
  },
  loadingText: {
    fontSize: 13,
    color: colors.gray,
    fontWeight: '700',
  },
  headerCard: {
    backgroundColor: colors.charcoal,
    borderRadius: 24,
    padding: 18,
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 14,
  },
  headerIcon: {
    width: 48,
    height: 48,
    borderRadius: 18,
    backgroundColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
  },
  headerTextGroup: {
    flex: 1,
    alignItems: 'flex-end',
  },
  headerTitle: {
    fontSize: 15,
    fontWeight: '900',
    color: colors.white,
  },
  headerSubtitle: {
    fontSize: 12,
    color: colors.grayLight,
    marginTop: 2,
  },
  codeHighlight: {
    color: colors.primaryLight,
    fontWeight: '900',
  },
  earningsGrid: {
    flexDirection: 'row',
    gap: 12,
  },
  earningBox: {
    flex: 1,
    backgroundColor: colors.white,
    borderRadius: 20,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
  },
  earningLabel: {
    fontSize: 11,
    color: colors.gray,
    fontWeight: '700',
    marginBottom: 4,
  },
  earningValue: {
    fontSize: 17,
    fontWeight: '900',
    color: colors.primary,
    fontFamily: 'Courier',
  },
  currency: {
    fontSize: 11,
    color: colors.gray,
    fontWeight: '700',
  },
  statsRow: {
    flexDirection: 'row',
    gap: 8,
  },
  smallStatCard: {
    flex: 1,
    backgroundColor: colors.white,
    borderRadius: 18,
    padding: 12,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
    gap: 4,
  },
  smallStatNum: {
    fontSize: 16,
    fontWeight: '900',
    color: colors.charcoal,
  },
  smallStatLabel: {
    fontSize: 9,
    fontWeight: '700',
    color: colors.gray,
  },
  referralCard: {
    backgroundColor: colors.white,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 10,
  },
  referralHeader: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 8,
  },
  referralTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
  },
  referralDesc: {
    fontSize: 11,
    color: colors.gray,
    textAlign: 'right',
    lineHeight: 18,
  },
  shareBtn: {
    backgroundColor: colors.primary,
    borderRadius: 18,
    paddingVertical: 12,
    flexDirection: 'row-reverse',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
  },
  shareBtnText: {
    color: colors.white,
    fontSize: 13,
    fontWeight: '800',
  },
  visitsSection: {
    backgroundColor: colors.white,
    borderRadius: 24,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 10,
  },
  visitsSectionTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
    marginBottom: 4,
  },
  visitRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: colors.offWhite,
  },
  visitEarnings: {
    alignItems: 'flex-start',
  },
  commissionText: {
    fontSize: 13,
    fontWeight: '900',
    color: colors.primary,
  },
  visitDate: {
    fontSize: 10,
    color: colors.gray,
    marginTop: 2,
  },
  visitInfo: {
    alignItems: 'flex-end',
  },
  studentName: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.charcoal,
  },
  studentPhone: {
    fontSize: 10,
    color: colors.gray,
    marginTop: 2,
  },
  emptyVisits: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 30,
    gap: 6,
  },
  emptyVisitsText: {
    fontSize: 12,
    color: colors.gray,
  },
});
