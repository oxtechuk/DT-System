import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { useAuth } from '../context/AuthContext';

export type TabKey = 'home' | 'menu' | 'teacher' | 'loyalty' | 'profile';

interface BottomNavProps {
  currentTab: TabKey;
  onTabChange: (tab: TabKey) => void;
}

export const BottomNav: React.FC<BottomNavProps> = ({ currentTab, onTabChange }) => {
  const { user } = useAuth();
  const isTeacher = user?.is_teacher === true;

  const handlePress = (tab: TabKey) => {
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
    onTabChange(tab);
  };

  return (
    <View style={styles.floatingWrapper}>
      <View style={styles.navBar}>
        {/* 1. الرئيسية */}
        <TouchableOpacity
          style={[styles.tabBtn, currentTab === 'home' && styles.activeTabBtn]}
          onPress={() => handlePress('home')}
          activeOpacity={0.8}
        >
          <Ionicons
            name={currentTab === 'home' ? 'grid' : 'grid-outline'}
            size={18}
            color={currentTab === 'home' ? colors.white : colors.gray}
          />
          <Text style={[styles.tabLabel, currentTab === 'home' && styles.activeTabLabel]}>
            الرئيسية
          </Text>
        </TouchableOpacity>

        {/* 2. المنيو والكافيه */}
        <TouchableOpacity
          style={[styles.tabBtn, currentTab === 'menu' && styles.activeTabBtn]}
          onPress={() => handlePress('menu')}
          activeOpacity={0.8}
        >
          <Ionicons
            name={currentTab === 'menu' ? 'cafe' : 'cafe-outline'}
            size={18}
            color={currentTab === 'menu' ? colors.white : colors.gray}
          />
          <Text style={[styles.tabLabel, currentTab === 'menu' && styles.activeTabLabel]}>
            الكافيه
          </Text>
        </TouchableOpacity>

        {/* 3. أرباحك (فقط للمدرسين) */}
        {isTeacher && (
          <TouchableOpacity
            style={[styles.tabBtn, currentTab === 'teacher' && styles.activeTabBtn]}
            onPress={() => handlePress('teacher')}
            activeOpacity={0.8}
          >
            <Ionicons
              name={currentTab === 'teacher' ? 'trending-up' : 'trending-up-outline'}
              size={18}
              color={currentTab === 'teacher' ? colors.white : colors.primary}
            />
            <Text
              style={[
                styles.tabLabel,
                styles.teacherTabLabel,
                currentTab === 'teacher' && styles.activeTabLabel,
              ]}
            >
              أرباحك
            </Text>
          </TouchableOpacity>
        )}

        {/* 4. الولاء */}
        <TouchableOpacity
          style={[styles.tabBtn, currentTab === 'loyalty' && styles.activeTabBtn]}
          onPress={() => handlePress('loyalty')}
          activeOpacity={0.8}
        >
          <Ionicons
            name={currentTab === 'loyalty' ? 'gift' : 'gift-outline'}
            size={18}
            color={currentTab === 'loyalty' ? colors.white : colors.gray}
          />
          <Text style={[styles.tabLabel, currentTab === 'loyalty' && styles.activeTabLabel]}>
            الولاء
          </Text>
        </TouchableOpacity>

        {/* 5. الحساب */}
        <TouchableOpacity
          style={[styles.tabBtn, currentTab === 'profile' && styles.activeTabBtn]}
          onPress={() => handlePress('profile')}
          activeOpacity={0.8}
        >
          <Ionicons
            name={currentTab === 'profile' ? 'person' : 'person-outline'}
            size={18}
            color={currentTab === 'profile' ? colors.white : colors.gray}
          />
          <Text style={[styles.tabLabel, currentTab === 'profile' && styles.activeTabLabel]}>
            حسابي
          </Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  floatingWrapper: {
    position: 'absolute',
    bottom: 20,
    left: 16,
    right: 16,
    alignItems: 'center',
    zIndex: 99,
  },
  navBar: {
    width: '100%',
    maxWidth: 420,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-around',
    backgroundColor: colors.white,
    paddingVertical: 8,
    paddingHorizontal: 6,
    borderRadius: 30,
    borderWidth: 1,
    borderColor: colors.border,
    shadowColor: colors.charcoal,
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.1,
    shadowRadius: 16,
    elevation: 8,
  },
  tabBtn: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 6,
    paddingHorizontal: 4,
    borderRadius: 22,
    gap: 3,
  },
  activeTabBtn: {
    backgroundColor: colors.primary,
  },
  tabLabel: {
    fontSize: 10,
    fontWeight: '700',
    color: colors.gray,
  },
  activeTabLabel: {
    color: colors.white,
  },
  teacherTabLabel: {
    color: colors.primary,
  },
});
