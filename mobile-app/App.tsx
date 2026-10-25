import React, { useState } from 'react';
import { View, StyleSheet, ActivityIndicator, Text } from 'react-native';
import { SafeAreaProvider, SafeAreaView } from 'react-native-safe-area-context';
import { StatusBar } from 'expo-status-bar';

import { colors } from './src/theme/colors';
import { AuthProvider, useAuth } from './src/context/AuthContext';
import { Header } from './src/components/Header';
import { BottomNav, TabKey } from './src/components/BottomNav';
import { LoginScreen } from './src/screens/LoginScreen';
import { RegisterScreen } from './src/screens/RegisterScreen';
import { HomeScreen } from './src/screens/HomeScreen';
import { MenuScreen } from './src/screens/MenuScreen';
import { TeacherScreen } from './src/screens/TeacherScreen';
import { LoyaltyScreen } from './src/screens/LoyaltyScreen';
import { ProfileScreen } from './src/screens/ProfileScreen';

const MainAppContent: React.FC = () => {
  const { user, token, isLoading } = useAuth();
  const [authView, setAuthView] = useState<'login' | 'register'>('login');
  const [currentTab, setCurrentTab] = useState<TabKey>('home');

  if (isLoading) {
    return (
      <View style={styles.splashContainer}>
        <View style={styles.splashBadge}>
          <Text style={styles.splashLogoText}>DDT</Text>
        </View>
        <Text style={styles.splashTitle}>DDT WORKING SPACE</Text>
        <ActivityIndicator size="small" color={colors.primary} style={{ marginTop: 16 }} />
      </View>
    );
  }

  // Not authenticated
  if (!token || !user) {
    return authView === 'login' ? (
      <LoginScreen onGoToRegister={() => setAuthView('register')} />
    ) : (
      <RegisterScreen onGoToLogin={() => setAuthView('login')} />
    );
  }

  // Authenticated: Render active tab screen
  return (
    <SafeAreaView style={styles.safeArea} edges={['top', 'left', 'right']}>
      <StatusBar style="dark" />

      {/* Top Header */}
      <Header
        onProfilePress={() => setCurrentTab('profile')}
        onLoyaltyPress={() => setCurrentTab('loyalty')}
      />

      {/* Screen Body */}
      <View style={styles.screenContainer}>
        {currentTab === 'home' && (
          <HomeScreen
            onNavigateToMenu={() => setCurrentTab('menu')}
            onNavigateToTeacher={() => setCurrentTab('teacher')}
            onNavigateToLoyalty={() => setCurrentTab('loyalty')}
          />
        )}
        {currentTab === 'menu' && <MenuScreen />}
        {currentTab === 'teacher' && <TeacherScreen />}
        {currentTab === 'loyalty' && <LoyaltyScreen />}
        {currentTab === 'profile' && <ProfileScreen />}
      </View>

      {/* Floating Solid Pill Navigation Island */}
      <BottomNav currentTab={currentTab} onTabChange={setCurrentTab} />
    </SafeAreaView>
  );
};

export default function App() {
  return (
    <SafeAreaProvider>
      <AuthProvider>
        <MainAppContent />
      </AuthProvider>
    </SafeAreaProvider>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: colors.offWhite,
  },
  screenContainer: {
    flex: 1,
  },
  splashContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: colors.offWhite,
    gap: 8,
  },
  splashBadge: {
    width: 72,
    height: 72,
    borderRadius: 26,
    backgroundColor: colors.primary,
    alignItems: 'center',
    justifyContent: 'center',
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.3,
    shadowRadius: 14,
    elevation: 6,
  },
  splashLogoText: {
    color: colors.white,
    fontSize: 24,
    fontWeight: '900',
    letterSpacing: 1.5,
  },
  splashTitle: {
    fontSize: 16,
    fontWeight: '900',
    color: colors.charcoal,
    marginTop: 8,
  },
});
