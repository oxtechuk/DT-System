import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../theme/colors';
import { useAuth } from '../context/AuthContext';

interface HeaderProps {
  title?: string;
  onProfilePress?: () => void;
  onLoyaltyPress?: () => void;
}

export const Header: React.FC<HeaderProps> = ({ title, onProfilePress, onLoyaltyPress }) => {
  const { user } = useAuth();
  const loyaltyVisits = user?.loyalty?.current_visits ?? 0;

  return (
    <View style={styles.container}>
      {/* Right / Start (RTL): User profile button */}
      <TouchableOpacity
        style={styles.avatarBtn}
        onPress={onProfilePress}
        activeOpacity={0.8}
      >
        <Text style={styles.avatarText}>
          {user?.name ? user.name.slice(0, 2) : 'DDT'}
        </Text>
      </TouchableOpacity>

      {/* Middle: Title or Brand */}
      <View style={styles.titleContainer}>
        <Text style={styles.brandTitle}>{title || 'DDT WORKING SPACE'}</Text>
      </View>

      {/* Left / End: Loyalty Pill Badge */}
      <TouchableOpacity
        style={styles.loyaltyPill}
        onPress={onLoyaltyPress}
        activeOpacity={0.8}
      >
        <View style={styles.greenDot} />
        <Text style={styles.loyaltyText}>{loyaltyVisits}/5</Text>
        <Ionicons name="gift-outline" size={13} color={colors.primary} />
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    height: 56,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    backgroundColor: colors.offWhite,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  avatarBtn: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: colors.charcoal,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: colors.border,
  },
  avatarText: {
    color: colors.white,
    fontSize: 12,
    fontWeight: 'bold',
  },
  titleContainer: {
    alignItems: 'center',
  },
  brandTitle: {
    fontSize: 13,
    fontWeight: '900',
    color: colors.charcoal,
    letterSpacing: 0.5,
  },
  loyaltyPill: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.sageLight,
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: colors.sage,
    gap: 4,
  },
  greenDot: {
    width: 6,
    height: 6,
    borderRadius: 3,
    backgroundColor: colors.primary,
  },
  loyaltyText: {
    fontSize: 11,
    fontWeight: '800',
    color: colors.primary,
  },
});
