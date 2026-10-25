import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
  ActivityIndicator,
  Platform,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import { apiRequest } from '../config/api';

interface HomeScreenProps {
  onNavigateToMenu: () => void;
  onNavigateToTeacher: () => void;
  onNavigateToLoyalty: () => void;
}

export const HomeScreen: React.FC<HomeScreenProps> = ({
  onNavigateToMenu,
  onNavigateToTeacher,
  onNavigateToLoyalty,
}) => {
  const { user, refreshProfile } = useAuth();
  const [dashboardData, setDashboardData] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchDashboard();
  }, []);

  const fetchDashboard = async () => {
    const res = await apiRequest('/portal/dashboard');
    if (res.success && res.data) {
      setDashboardData(res.data);
    }
    setIsLoading(false);
  };

  const onRefresh = async () => {
    setRefreshing(true);
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
    await Promise.all([fetchDashboard(), refreshProfile()]);
    setRefreshing(false);
  };

  const activeSession = dashboardData?.active_session;
  const recentOrders = dashboardData?.recent_orders || [];
  const isTeacher = user?.is_teacher === true;
  const loyaltyVisits = user?.loyalty?.current_visits ?? 0;

  if (isLoading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>جارٍ تحميل بياناتك في DDT...</Text>
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      contentContainerStyle={styles.content}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} />
      }
    >
      {/* Welcome Banner */}
      <View style={styles.welcomeCard}>
        <View style={styles.welcomeTextGroup}>
          <Text style={styles.welcomeSubtitle}>مرحباً بك مجدداً 👋</Text>
          <Text style={styles.welcomeName}>{user?.name || 'عضو مساحة DDT'}</Text>
        </View>
        <View style={styles.logoCircle}>
          <Ionicons name="sparkles" size={20} color={colors.primary} />
        </View>
      </View>

      {/* Active Workspace Session Card */}
      {activeSession ? (
        <View style={styles.activeSessionCard}>
          <View style={styles.sessionHeader}>
            <View style={styles.sessionStatusPill}>
              <View style={styles.pulsingDot} />
              <Text style={styles.sessionStatusText}>جلسة نشطة حالياً</Text>
            </View>
            <Text style={styles.roomName}>{activeSession.room_name}</Text>
          </View>

          <View style={styles.sessionStatsRow}>
            <View style={styles.statBox}>
              <Text style={styles.statLabel}>مدة الجلوس</Text>
              <Text style={styles.statValue}>{activeSession.duration_formatted}</Text>
            </View>
            <View style={styles.statDivider} />
            <View style={styles.statBox}>
              <Text style={styles.statLabel}>الحساب التقديري</Text>
              <Text style={[styles.statValue, { color: colors.primary }]}>
                {activeSession.current_total.toFixed(2)} ج.م
              </Text>
            </View>
          </View>
        </View>
      ) : (
        <View style={styles.noSessionCard}>
          <Ionicons name="time-outline" size={28} color={colors.gray} />
          <View style={styles.noSessionTextGroup}>
            <Text style={styles.noSessionTitle}>لا توجد جلسة نشطة الآن</Text>
            <Text style={styles.noSessionSubtitle}>تفضل بزيارة الاستقبال لبدء جلستك في المساحة</Text>
          </View>
        </View>
      )}

      {/* Digital Member QR Code Card */}
      <View style={styles.qrCard}>
        <View style={styles.qrHeader}>
          <Ionicons name="qr-code-outline" size={22} color={colors.primary} />
          <Text style={styles.qrTitle}>كود العضوية الرقمي</Text>
        </View>
        <Text style={styles.qrDesc}>
          أظهر هذا الكود للكاشير أو الاستقبال لتسجيل الزيارات وبدء الجلسات
        </Text>
        <View style={styles.qrCodeBox}>
          <Text style={styles.qrCodeText}>
            {dashboardData?.customer?.qr_code || `DDT-CUST-${user?.id}`}
          </Text>
          <Text style={styles.qrCodeHint}>DDT MEMBER ID</Text>
        </View>
      </View>

      {/* Quick Action Grid */}
      <View style={styles.actionsGrid}>
        {/* 1. Cafe Order */}
        <TouchableOpacity
          style={styles.actionCard}
          onPress={onNavigateToMenu}
          activeOpacity={0.85}
        >
          <View style={[styles.actionIconBox, { backgroundColor: colors.sageLight }]}>
            <Ionicons name="cafe" size={24} color={colors.primary} />
          </View>
          <Text style={styles.actionTitle}>كافيه DDT</Text>
          <Text style={styles.actionDesc}>اطلب مشروبك لمكان جلوسك</Text>
        </TouchableOpacity>

        {/* 2. Loyalty */}
        <TouchableOpacity
          style={styles.actionCard}
          onPress={onNavigateToLoyalty}
          activeOpacity={0.85}
        >
          <View style={[styles.actionIconBox, { backgroundColor: colors.amberLight }]}>
            <Ionicons name="gift" size={24} color={colors.amber} />
          </View>
          <Text style={styles.actionTitle}>بطاقة الولاء</Text>
          <Text style={styles.actionDesc}>{loyaltyVisits}/5 زيارات مكتملة</Text>
        </TouchableOpacity>

        {/* 3. Teacher Portal (If teacher) */}
        {isTeacher && (
          <TouchableOpacity
            style={[styles.actionCard, styles.teacherCard]}
            onPress={onNavigateToTeacher}
            activeOpacity={0.85}
          >
            <View style={[styles.actionIconBox, { backgroundColor: colors.sageLight }]}>
              <Ionicons name="trending-up" size={24} color={colors.primary} />
            </View>
            <Text style={styles.actionTitle}>بوابة أرباحك</Text>
            <Text style={styles.actionDesc}>متابعة الطلاب والكوميشن</Text>
          </TouchableOpacity>
        )}
      </View>

      {/* Recent Orders List */}
      {recentOrders.length > 0 && (
        <View style={styles.recentOrdersSection}>
          <Text style={styles.sectionTitle}>أحدث طلبات الكافيه</Text>
          {recentOrders.map((order: any) => (
            <View key={order.id} style={styles.orderRow}>
              <View style={styles.orderStatusPill}>
                <Text style={styles.orderStatusText}>
                  {order.fulfillment_status === 'delivered'
                    ? 'تم التسليم ✅'
                    : order.fulfillment_status === 'preparing'
                    ? 'قيد التحضير ⏳'
                    : 'جديد 🕒'}
                </Text>
              </View>
              <View style={styles.orderInfo}>
                <Text style={styles.orderNumber}>{order.order_number}</Text>
                <Text style={styles.orderLocation}>{order.location}</Text>
              </View>
              <Text style={styles.orderTotal}>{order.total.toFixed(2)} ج.م</Text>
            </View>
          ))}
        </View>
      )}

      {/* Bottom Padding for floating nav */}
      <View style={{ height: 100 }} />
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
    gap: 12,
  },
  loadingText: {
    fontSize: 13,
    fontWeight: '700',
    color: colors.gray,
  },
  welcomeCard: {
    backgroundColor: colors.white,
    borderRadius: 24,
    padding: 18,
    borderWidth: 1,
    borderColor: colors.border,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  welcomeTextGroup: {
    alignItems: 'flex-start',
  },
  welcomeSubtitle: {
    fontSize: 12,
    color: colors.gray,
    fontWeight: '600',
  },
  welcomeName: {
    fontSize: 17,
    fontWeight: '900',
    color: colors.charcoal,
    marginTop: 2,
  },
  logoCircle: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: colors.sageLight,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: colors.sage,
  },
  activeSessionCard: {
    backgroundColor: colors.white,
    borderRadius: 24,
    padding: 18,
    borderWidth: 1,
    borderColor: colors.primary,
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 10,
    elevation: 4,
  },
  sessionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    marginBottom: 14,
  },
  sessionStatusPill: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.sageLight,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 16,
    gap: 6,
  },
  pulsingDot: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: colors.primary,
  },
  sessionStatusText: {
    fontSize: 11,
    fontWeight: '800',
    color: colors.primary,
  },
  roomName: {
    fontSize: 14,
    fontWeight: '800',
    color: colors.charcoal,
  },
  sessionStatsRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-around',
    backgroundColor: colors.offWhite,
    borderRadius: 18,
    paddingVertical: 12,
  },
  statBox: {
    alignItems: 'center',
    flex: 1,
  },
  statLabel: {
    fontSize: 11,
    color: colors.gray,
    fontWeight: '600',
    marginBottom: 2,
  },
  statValue: {
    fontSize: 15,
    fontWeight: '900',
    color: colors.charcoal,
  },
  statDivider: {
    width: 1,
    height: 28,
    backgroundColor: colors.border,
  },
  noSessionCard: {
    backgroundColor: colors.white,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 14,
  },
  noSessionTextGroup: {
    flex: 1,
    alignItems: 'flex-end',
  },
  noSessionTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
  },
  noSessionSubtitle: {
    fontSize: 11,
    color: colors.gray,
    marginTop: 2,
  },
  qrCard: {
    backgroundColor: colors.white,
    borderRadius: 24,
    padding: 18,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
  },
  qrHeader: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 8,
    marginBottom: 4,
  },
  qrTitle: {
    fontSize: 14,
    fontWeight: '800',
    color: colors.charcoal,
  },
  qrDesc: {
    fontSize: 11,
    color: colors.gray,
    textAlign: 'center',
    marginBottom: 14,
    paddingHorizontal: 12,
  },
  qrCodeBox: {
    backgroundColor: colors.offWhite,
    borderRadius: 16,
    paddingVertical: 12,
    paddingHorizontal: 20,
    borderWidth: 1.5,
    borderColor: colors.border,
    borderStyle: 'dashed',
    alignItems: 'center',
    width: '100%',
  },
  qrCodeText: {
    fontSize: 16,
    fontWeight: '900',
    color: colors.charcoal,
    letterSpacing: 2,
    fontFamily: Platform.OS === 'ios' ? 'Courier' : 'monospace',
  },
  qrCodeHint: {
    fontSize: 9,
    fontWeight: '700',
    color: colors.primary,
    marginTop: 4,
    letterSpacing: 1,
  },
  actionsGrid: {
    flexDirection: 'row-reverse',
    flexWrap: 'wrap',
    gap: 12,
  },
  actionCard: {
    flex: 1,
    minWidth: '45%',
    backgroundColor: colors.white,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'flex-end',
  },
  teacherCard: {
    borderColor: colors.primary,
    backgroundColor: colors.sageLight,
  },
  actionIconBox: {
    width: 44,
    height: 44,
    borderRadius: 16,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 10,
  },
  actionTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
  },
  actionDesc: {
    fontSize: 10,
    color: colors.gray,
    marginTop: 2,
  },
  recentOrdersSection: {
    backgroundColor: colors.white,
    borderRadius: 24,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 10,
  },
  sectionTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
    marginBottom: 4,
  },
  orderRow: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 8,
    borderBottomWidth: 1,
    borderBottomColor: colors.offWhite,
  },
  orderInfo: {
    flex: 1,
    alignItems: 'flex-end',
    marginRight: 10,
  },
  orderNumber: {
    fontSize: 12,
    fontWeight: '800',
    color: colors.charcoal,
  },
  orderLocation: {
    fontSize: 10,
    color: colors.gray,
    marginTop: 2,
  },
  orderTotal: {
    fontSize: 12,
    fontWeight: '900',
    color: colors.primary,
  },
  orderStatusPill: {
    backgroundColor: colors.offWhite,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
  },
  orderStatusText: {
    fontSize: 10,
    fontWeight: '700',
    color: colors.charcoal,
  },
});
