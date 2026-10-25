import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  RefreshControl,
  ActivityIndicator,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { useAuth } from '../context/AuthContext';
import { apiRequest } from '../config/api';

export const LoyaltyScreen: React.FC = () => {
  const { user, refreshProfile } = useAuth();
  const [loyaltyData, setLoyaltyData] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchLoyalty();
  }, []);

  const fetchLoyalty = async () => {
    const res = await apiRequest('/portal/loyalty');
    if (res.success && res.data) {
      setLoyaltyData(res.data);
    }
    setIsLoading(false);
  };

  const onRefresh = async () => {
    setRefreshing(true);
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
    await Promise.all([fetchLoyalty(), refreshProfile()]);
    setRefreshing(false);
  };

  if (isLoading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>جارٍ تحميل بطاقة الولاء...</Text>
      </View>
    );
  }

  const loyalty = loyaltyData?.loyalty || user?.loyalty || {};
  const currentVisits = loyalty.current_visits ?? 0;
  const targetVisits = loyalty.target_visits ?? 5;
  const completedCycles = loyalty.completed_cycles ?? 0;
  const hasReward = loyalty.has_unclaimed_free_drink === true;

  return (
    <ScrollView
      style={styles.container}
      contentContainerStyle={styles.content}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={colors.primary} />
      }
    >
      {/* Loyalty Stamp Card */}
      <View style={styles.stampCard}>
        <View style={styles.cardTopRow}>
          <View style={styles.cardBadge}>
            <Text style={styles.cardBadgeText}>برنامج ولاء DDT</Text>
          </View>
          <Text style={styles.cardTitle}>بطاقة الزيارات</Text>
        </View>

        <Text style={styles.cardDesc}>
          أتمم 5 زيارات لمساحة العمل واحصل على مشروبك القادم مجاناً من كافيه DDT
        </Text>

        {/* 5 Stamp Circles */}
        <View style={styles.stampsRow}>
          {[1, 2, 3, 4, 5].map((num) => {
            const isCompleted = num <= currentVisits;
            const isLast = num === 5;

            return (
              <View
                key={num}
                style={[
                  styles.stampCircle,
                  isCompleted && styles.stampCircleCompleted,
                  isLast && styles.stampCircleGift,
                ]}
              >
                {isCompleted ? (
                  <Ionicons name="checkmark" size={20} color={colors.white} />
                ) : isLast ? (
                  <Ionicons name="gift" size={20} color={colors.primary} />
                ) : (
                  <Text style={styles.stampNumText}>{num}</Text>
                )}
              </View>
            );
          })}
        </View>

        {/* Progress Bar */}
        <View style={styles.progressContainer}>
          <View
            style={[
              styles.progressBar,
              { width: `${Math.min((currentVisits / targetVisits) * 100, 100)}%` },
            ]}
          />
        </View>

        <View style={styles.progressInfoRow}>
          <Text style={styles.progressStatusText}>
            {targetVisits - currentVisits > 0
              ? `متبقي ${targetVisits - currentVisits} زيارة للمشروب المجاني`
              : 'مبارك! مشروبك المجاني جاهز للاستلام 🎁'}
          </Text>
          <Text style={styles.progressNumText}>
            {currentVisits} من {targetVisits}
          </Text>
        </View>
      </View>

      {/* Unclaimed Reward Alert if available */}
      {hasReward && (
        <View style={styles.rewardAlert}>
          <Ionicons name="gift" size={24} color={colors.primary} />
          <View style={styles.rewardAlertTextGroup}>
            <Text style={styles.rewardAlertTitle}>لديك مشروب مجاني متاح الآن!</Text>
            <Text style={styles.rewardAlertSubtitle}>
              أخبر الكاشير عند طلب مشروبك القادم لخصم المشروب مجاناً من حسابك.
            </Text>
          </View>
        </View>
      )}

      {/* Loyalty Lifetime Stats */}
      <View style={styles.statsCard}>
        <Text style={styles.statsCardTitle}>إحصائيات الولاء الخاصة بك</Text>
        <View style={styles.statsGrid}>
          <View style={styles.statBox}>
            <Text style={styles.statNumber}>{completedCycles}</Text>
            <Text style={styles.statLabel}>مشروبات مجانية مكتسبة</Text>
          </View>
          <View style={styles.statDivider} />
          <View style={styles.statBox}>
            <Text style={styles.statNumber}>{currentVisits}</Text>
            <Text style={styles.statLabel}>زيارات الدورة الحالية</Text>
          </View>
        </View>
      </View>

      {/* How it works info */}
      <View style={styles.rulesCard}>
        <Text style={styles.rulesTitle}>كيف يعمل نظام الولاء؟</Text>
        <View style={styles.ruleItem}>
          <Ionicons name="checkmark-circle-outline" size={18} color={colors.primary} />
          <Text style={styles.ruleText}>يتم تسجيل زيارة تلقائياً عند قضاء جلسة في مساحة العمل.</Text>
        </View>
        <View style={styles.ruleItem}>
          <Ionicons name="checkmark-circle-outline" size={18} color={colors.primary} />
          <Text style={styles.ruleText}>عند إتمام الزيارة الخامسة، يُفتح لك كوبون مشروب مجاني فوراً.</Text>
        </View>
        <View style={styles.ruleItem}>
          <Ionicons name="checkmark-circle-outline" size={18} color={colors.primary} />
          <Text style={styles.ruleText}>بعد استلام الهدية، تبدأ دورة جديدة بـ 5 زيارات إضافية.</Text>
        </View>
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
  stampCard: {
    backgroundColor: colors.white,
    borderRadius: 26,
    padding: 20,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 12,
  },
  cardTopRow: {
    flexDirection: 'row-reverse',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  cardBadge: {
    backgroundColor: colors.sageLight,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 14,
  },
  cardBadgeText: {
    fontSize: 10,
    fontWeight: '800',
    color: colors.primary,
  },
  cardTitle: {
    fontSize: 15,
    fontWeight: '900',
    color: colors.charcoal,
  },
  cardDesc: {
    fontSize: 12,
    color: colors.gray,
    textAlign: 'right',
    lineHeight: 18,
  },
  stampsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginVertical: 10,
  },
  stampCircle: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: colors.offWhite,
    borderWidth: 1.5,
    borderColor: colors.border,
    alignItems: 'center',
    justifyContent: 'center',
  },
  stampCircleCompleted: {
    backgroundColor: colors.primary,
    borderColor: colors.primary,
  },
  stampCircleGift: {
    borderColor: colors.primary,
    backgroundColor: colors.sageLight,
  },
  stampNumText: {
    fontSize: 14,
    fontWeight: '800',
    color: colors.gray,
  },
  progressContainer: {
    height: 8,
    backgroundColor: colors.offWhite,
    borderRadius: 4,
    overflow: 'hidden',
    marginTop: 4,
  },
  progressBar: {
    height: '100%',
    backgroundColor: colors.primary,
    borderRadius: 4,
  },
  progressInfoRow: {
    flexDirection: 'row-reverse',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 2,
  },
  progressStatusText: {
    fontSize: 11,
    fontWeight: '700',
    color: colors.charcoal,
  },
  progressNumText: {
    fontSize: 12,
    fontWeight: '900',
    color: colors.primary,
    fontFamily: 'Courier',
  },
  rewardAlert: {
    backgroundColor: colors.sageLight,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1.5,
    borderColor: colors.primary,
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 12,
  },
  rewardAlertTextGroup: {
    flex: 1,
    alignItems: 'flex-end',
  },
  rewardAlertTitle: {
    fontSize: 13,
    fontWeight: '900',
    color: colors.primary,
  },
  rewardAlertSubtitle: {
    fontSize: 11,
    color: colors.charcoal,
    marginTop: 2,
    textAlign: 'right',
  },
  statsCard: {
    backgroundColor: colors.white,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 12,
  },
  statsCardTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
  },
  statsGrid: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  statBox: {
    flex: 1,
    alignItems: 'center',
  },
  statNumber: {
    fontSize: 20,
    fontWeight: '900',
    color: colors.primary,
  },
  statLabel: {
    fontSize: 10,
    color: colors.gray,
    fontWeight: '700',
    marginTop: 2,
  },
  statDivider: {
    width: 1,
    height: 32,
    backgroundColor: colors.border,
  },
  rulesCard: {
    backgroundColor: colors.white,
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 10,
  },
  rulesTitle: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
    marginBottom: 4,
  },
  ruleItem: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 8,
  },
  ruleText: {
    fontSize: 11,
    color: colors.gray,
    flex: 1,
    textAlign: 'right',
  },
});
