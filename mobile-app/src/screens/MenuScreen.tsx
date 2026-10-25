import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  Modal,
  Alert,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as Haptics from 'expo-haptics';
import { colors } from '../theme/colors';
import { apiRequest } from '../config/api';

interface CartItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
}

export const MenuScreen: React.FC = () => {
  const [categories, setCategories] = useState<any[]>([]);
  const [products, setProducts] = useState<any[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<number | 'all'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [cart, setCart] = useState<Record<number, CartItem>>({});
  const [deliveryLocation, setDeliveryLocation] = useState('المساحة العامة');
  const [orderNotes, setOrderNotes] = useState('');
  const [isLoading, setIsLoading] = useState(true);
  const [isModalVisible, setIsModalVisible] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    fetchMenuData();
  }, []);

  const fetchMenuData = async () => {
    setIsLoading(true);
    const [catRes, prodRes] = await Promise.all([
      apiRequest('/portal/categories'),
      apiRequest('/portal/products'),
    ]);

    if (catRes.success && catRes.data) {
      setCategories(catRes.data);
    }
    if (prodRes.success && prodRes.data) {
      setProducts(prodRes.data);
    }
    setIsLoading(false);
  };

  const updateQuantity = (product: any, delta: number) => {
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
    setCart((prev) => {
      const existing = prev[product.id];
      const newQty = (existing ? existing.quantity : 0) + delta;

      if (newQty <= 0) {
        const next = { ...prev };
        delete next[product.id];
        return next;
      }

      return {
        ...prev,
        [product.id]: {
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: newQty,
        },
      };
    });
  };

  const totalCartCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
  const totalCartPrice = Object.values(cart).reduce(
    (sum, item) => sum + item.quantity * item.price,
    0
  );

  const filteredProducts = products.filter((prod) => {
    const matchCat = selectedCategory === 'all' || prod.category_id === selectedCategory;
    const matchSearch =
      !searchQuery.trim() ||
      prod.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      (prod.description && prod.description.toLowerCase().includes(searchQuery.toLowerCase()));
    return matchCat && matchSearch;
  });

  const handleSubmitOrder = async () => {
    if (totalCartCount === 0) return;

    setIsSubmitting(true);
    Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Medium);

    const itemsPayload = Object.values(cart).map((item) => ({
      product_id: item.id,
      quantity: item.quantity,
    }));

    const res = await apiRequest('/portal/orders', {
      method: 'POST',
      body: {
        items: itemsPayload,
        table_or_room_name: deliveryLocation,
        customer_notes: orderNotes.trim() || undefined,
      },
    });

    setIsSubmitting(false);

    if (res.success) {
      Haptics.notificationAsync(Haptics.NotificationFeedbackType.Success);
      setIsModalVisible(false);
      setCart({});
      setOrderNotes('');
      Alert.alert('تم استلام طلبك! ☕', 'سيقوم الكاشير وفريق العمل بتحضير مشروبك وتوصيله فوراً.');
    } else {
      Haptics.notificationAsync(Haptics.NotificationFeedbackType.Error);
      Alert.alert('خطأ', res.message || 'تعذر إرسال الطلب، يرجى المحاولة ثانية.');
    }
  };

  return (
    <View style={styles.container}>
      {/* Search & Location Header Bar */}
      <View style={styles.headerControls}>
        {/* Delivery Location Input */}
        <View style={styles.locationRow}>
          <Ionicons name="location" size={16} color={colors.primary} />
          <Text style={styles.locationLabel}>مكان الاستلام:</Text>
          <TextInput
            style={styles.locationInput}
            value={deliveryLocation}
            onChangeText={setDeliveryLocation}
            placeholder="رقم الطاولة أو اسم القاعة..."
            placeholderTextColor={colors.gray}
            textAlign="right"
          />
        </View>

        {/* Search Bar */}
        <View style={styles.searchWrapper}>
          <TextInput
            style={styles.searchInput}
            placeholder="ابحث باسم المشروب أو الصنف..."
            placeholderTextColor={colors.gray}
            value={searchQuery}
            onChangeText={setSearchQuery}
            textAlign="right"
          />
          <Ionicons name="search" size={18} color={colors.gray} style={styles.searchIcon} />
        </View>

        {/* Category Pills Navigation */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.categoriesScroll}
        >
          {/* 'All' Tab */}
          <TouchableOpacity
            style={[styles.catPill, selectedCategory === 'all' && styles.catPillActive]}
            onPress={() => {
              Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
              setSelectedCategory('all');
            }}
          >
            <Text
              style={[styles.catPillText, selectedCategory === 'all' && styles.catPillTextActive]}
            >
              الكل ({products.length})
            </Text>
          </TouchableOpacity>

          {categories.map((cat) => (
            <TouchableOpacity
              key={cat.id}
              style={[styles.catPill, selectedCategory === cat.id && styles.catPillActive]}
              onPress={() => {
                Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Light);
                setSelectedCategory(cat.id);
              }}
            >
              <Text
                style={[styles.catPillText, selectedCategory === cat.id && styles.catPillTextActive]}
              >
                {cat.name} ({cat.products_count})
              </Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      {/* Products List */}
      {isLoading ? (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={colors.primary} />
          <Text style={styles.loadingText}>جارٍ تحميل أصناف الكافيه...</Text>
        </View>
      ) : (
        <ScrollView contentContainerStyle={styles.productsScroll}>
          {filteredProducts.map((prod) => {
            const currentQty = cart[prod.id]?.quantity || 0;
            const isSelected = currentQty > 0;

            return (
              <View
                key={prod.id}
                style={[styles.productCard, isSelected && styles.productCardActive]}
              >
                {/* Stepper (- 0 +) on Left */}
                <View style={styles.stepperBox}>
                  <TouchableOpacity
                    style={[styles.stepperBtn, styles.minusBtn]}
                    onPress={() => updateQuantity(prod, -1)}
                    activeOpacity={0.7}
                  >
                    <Ionicons name="remove" size={16} color={colors.charcoal} />
                  </TouchableOpacity>
                  <Text style={styles.qtyText}>{currentQty}</Text>
                  <TouchableOpacity
                    style={[styles.stepperBtn, styles.plusBtn]}
                    onPress={() => updateQuantity(prod, 1)}
                    activeOpacity={0.7}
                  >
                    <Ionicons name="add" size={16} color={colors.white} />
                  </TouchableOpacity>
                </View>

                {/* Details on Right */}
                <View style={styles.productDetails}>
                  <Text style={styles.productName}>{prod.name}</Text>
                  <View style={styles.priceAndBadgeRow}>
                    <Text style={styles.productPrice}>
                      {prod.price.toFixed(2)} <Text style={styles.currency}>ج.م</Text>
                    </Text>
                    <View style={styles.categoryBadge}>
                      <Text style={styles.categoryBadgeText}>{prod.category_name}</Text>
                    </View>
                  </View>
                  {prod.description ? (
                    <Text style={styles.productDesc} numberOfLines={1}>
                      {prod.description}
                    </Text>
                  ) : null}
                </View>

                {/* Beverage Icon Squircle */}
                <View style={styles.iconBox}>
                  <Ionicons name="cafe-outline" size={22} color={colors.primary} />
                </View>
              </View>
            );
          })}

          {filteredProducts.length === 0 && (
            <View style={styles.emptyContainer}>
              <Ionicons name="search-outline" size={36} color={colors.gray} />
              <Text style={styles.emptyTitle}>لا توجد أصناف مطابقة</Text>
              <Text style={styles.emptyDesc}>جرّب تغيير كلمات البحث أو اختيار قسم آخر</Text>
            </View>
          )}

          <View style={{ height: 160 }} />
        </ScrollView>
      )}

      {/* Floating Bottom Cart Bar */}
      {totalCartCount > 0 && (
        <View style={styles.floatingCartWrapper}>
          <View style={styles.cartBar}>
            <TouchableOpacity
              style={styles.checkoutBtn}
              onPress={() => setIsModalVisible(true)}
              activeOpacity={0.85}
            >
              <Ionicons name="arrow-back" size={16} color={colors.white} />
              <Text style={styles.checkoutBtnText}>تأكيد الطلب</Text>
            </TouchableOpacity>

            <View style={styles.cartSummary}>
              <Text style={styles.cartPriceText}>{totalCartPrice.toFixed(2)} ج.م</Text>
              <View style={styles.cartCountBadge}>
                <Text style={styles.cartCountText}>{totalCartCount} مشروب</Text>
              </View>
            </View>
          </View>
        </View>
      )}

      {/* Order Confirmation Modal */}
      <Modal visible={isModalVisible} animationType="slide" transparent>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <TouchableOpacity onPress={() => setIsModalVisible(false)} style={styles.closeBtn}>
                <Ionicons name="close" size={20} color={colors.charcoal} />
              </TouchableOpacity>
              <Text style={styles.modalTitle}>تأكيد طلب المشروبات</Text>
            </View>

            {/* Selected Items List */}
            <ScrollView style={styles.modalItemsScroll}>
              {Object.values(cart).map((item) => (
                <View key={item.id} style={styles.modalItemRow}>
                  <Text style={styles.modalItemTotal}>
                    {(item.price * item.quantity).toFixed(2)} ج.م
                  </Text>
                  <Text style={styles.modalItemName}>
                    {item.name} × {item.quantity}
                  </Text>
                </View>
              ))}
            </ScrollView>

            {/* Preparation Notes */}
            <View style={styles.notesBox}>
              <Text style={styles.notesLabel}>ملاحظات التحضير (اختياري):</Text>
              <TextInput
                style={styles.notesInput}
                placeholder="مثلاً: سكر خفيف، بدون ثلج، قهوة مظبوط..."
                placeholderTextColor={colors.gray}
                value={orderNotes}
                onChangeText={setOrderNotes}
                textAlign="right"
              />
            </View>

            {/* Location Confirmation */}
            <View style={styles.modalLocationRow}>
              <Text style={styles.modalLocationValue}>{deliveryLocation}</Text>
              <Text style={styles.modalLocationLabel}>مكان التسليم:</Text>
            </View>

            {/* Total and Submit */}
            <View style={styles.modalFooter}>
              <View>
                <Text style={styles.modalTotalLabel}>المبلغ الإجمالي</Text>
                <Text style={styles.modalTotalValue}>{totalCartPrice.toFixed(2)} ج.م</Text>
              </View>

              <TouchableOpacity
                style={[styles.modalSubmitBtn, isSubmitting && styles.modalSubmitBtnDisabled]}
                onPress={handleSubmitOrder}
                disabled={isSubmitting}
              >
                {isSubmitting ? (
                  <ActivityIndicator color={colors.white} />
                ) : (
                  <Text style={styles.modalSubmitText}>إرسال للكاشير</Text>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.offWhite,
  },
  headerControls: {
    backgroundColor: colors.white,
    paddingTop: 12,
    paddingHorizontal: 16,
    paddingBottom: 8,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
    gap: 10,
  },
  locationRow: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    backgroundColor: colors.offWhite,
    borderRadius: 16,
    paddingHorizontal: 12,
    height: 40,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 6,
  },
  locationLabel: {
    fontSize: 11,
    fontWeight: '700',
    color: colors.charcoal,
  },
  locationInput: {
    flex: 1,
    fontSize: 12,
    fontWeight: '600',
    color: colors.charcoal,
  },
  searchWrapper: {
    position: 'relative',
    flexDirection: 'row',
    alignItems: 'center',
  },
  searchInput: {
    flex: 1,
    height: 42,
    backgroundColor: colors.offWhite,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.border,
    paddingRight: 38,
    paddingLeft: 12,
    fontSize: 12,
    color: colors.charcoal,
    fontWeight: '600',
  },
  searchIcon: {
    position: 'absolute',
    right: 12,
  },
  categoriesScroll: {
    gap: 8,
    paddingVertical: 4,
  },
  catPill: {
    paddingHorizontal: 14,
    paddingVertical: 6,
    borderRadius: 20,
    backgroundColor: colors.offWhite,
    borderWidth: 1,
    borderColor: colors.border,
  },
  catPillActive: {
    backgroundColor: colors.primary,
    borderColor: colors.primary,
  },
  catPillText: {
    fontSize: 11,
    fontWeight: '700',
    color: colors.gray,
  },
  catPillTextActive: {
    color: colors.white,
  },
  productsScroll: {
    padding: 16,
    gap: 10,
  },
  loadingContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 10,
  },
  loadingText: {
    fontSize: 13,
    color: colors.gray,
    fontWeight: '700',
  },
  productCard: {
    backgroundColor: colors.white,
    borderRadius: 20,
    padding: 12,
    borderWidth: 1,
    borderColor: colors.border,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 10,
  },
  productCardActive: {
    borderColor: colors.primary,
    backgroundColor: colors.sageLight,
  },
  iconBox: {
    width: 48,
    height: 48,
    borderRadius: 16,
    backgroundColor: colors.offWhite,
    borderWidth: 1,
    borderColor: colors.border,
    alignItems: 'center',
    justifyContent: 'center',
  },
  productDetails: {
    flex: 1,
    alignItems: 'flex-end',
  },
  productName: {
    fontSize: 13,
    fontWeight: '800',
    color: colors.charcoal,
    textAlign: 'right',
  },
  priceAndBadgeRow: {
    flexDirection: 'row-reverse',
    alignItems: 'center',
    gap: 8,
    marginTop: 4,
  },
  productPrice: {
    fontSize: 13,
    fontWeight: '900',
    color: colors.primary,
    fontFamily: 'Courier',
  },
  currency: {
    fontSize: 10,
    color: colors.gray,
    fontWeight: '700',
  },
  categoryBadge: {
    backgroundColor: colors.offWhite,
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: colors.border,
  },
  categoryBadgeText: {
    fontSize: 9,
    fontWeight: '700',
    color: colors.gray,
  },
  productDesc: {
    fontSize: 10,
    color: colors.gray,
    marginTop: 3,
    textAlign: 'right',
  },
  stepperBox: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.offWhite,
    borderRadius: 20,
    padding: 3,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 4,
  },
  stepperBtn: {
    width: 28,
    height: 28,
    borderRadius: 14,
    alignItems: 'center',
    justifyContent: 'center',
  },
  minusBtn: {
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
  },
  plusBtn: {
    backgroundColor: colors.primary,
  },
  qtyText: {
    fontSize: 12,
    fontWeight: '900',
    color: colors.charcoal,
    width: 20,
    textAlign: 'center',
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 60,
    gap: 8,
  },
  emptyTitle: {
    fontSize: 15,
    fontWeight: '800',
    color: colors.charcoal,
  },
  emptyDesc: {
    fontSize: 12,
    color: colors.gray,
  },
  floatingCartWrapper: {
    position: 'absolute',
    bottom: 86,
    left: 16,
    right: 16,
    zIndex: 90,
  },
  cartBar: {
    backgroundColor: colors.charcoal,
    borderRadius: 28,
    paddingVertical: 10,
    paddingHorizontal: 16,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    shadowColor: colors.charcoal,
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.25,
    shadowRadius: 12,
    elevation: 8,
  },
  cartSummary: {
    alignItems: 'flex-end',
  },
  cartPriceText: {
    color: colors.white,
    fontSize: 14,
    fontWeight: '900',
  },
  cartCountBadge: {
    backgroundColor: colors.primary,
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 10,
    marginTop: 2,
  },
  cartCountText: {
    color: colors.white,
    fontSize: 10,
    fontWeight: '800',
  },
  checkoutBtn: {
    backgroundColor: colors.primary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  checkoutBtnText: {
    color: colors.white,
    fontSize: 12,
    fontWeight: '800',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    backgroundColor: colors.white,
    borderTopLeftRadius: 32,
    borderTopRightRadius: 32,
    padding: 24,
    maxHeight: '80%',
    gap: 14,
  },
  modalHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  modalTitle: {
    fontSize: 15,
    fontWeight: '800',
    color: colors.charcoal,
  },
  closeBtn: {
    padding: 4,
  },
  modalItemsScroll: {
    maxHeight: 160,
  },
  modalItemRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 6,
    borderBottomWidth: 1,
    borderBottomColor: colors.offWhite,
  },
  modalItemName: {
    fontSize: 12,
    fontWeight: '700',
    color: colors.charcoal,
  },
  modalItemTotal: {
    fontSize: 12,
    fontWeight: '900',
    color: colors.primary,
  },
  notesBox: {
    gap: 6,
  },
  notesLabel: {
    fontSize: 11,
    fontWeight: '700',
    color: colors.charcoal,
    textAlign: 'right',
  },
  notesInput: {
    height: 42,
    backgroundColor: colors.offWhite,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: colors.border,
    paddingHorizontal: 12,
    fontSize: 12,
    color: colors.charcoal,
  },
  modalLocationRow: {
    flexDirection: 'row-reverse',
    justifyContent: 'space-between',
    backgroundColor: colors.offWhite,
    padding: 10,
    borderRadius: 12,
  },
  modalLocationLabel: {
    fontSize: 11,
    color: colors.gray,
    fontWeight: '700',
  },
  modalLocationValue: {
    fontSize: 11,
    fontWeight: '800',
    color: colors.primary,
  },
  modalFooter: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingTop: 10,
    borderTopWidth: 1,
    borderTopColor: colors.border,
  },
  modalTotalLabel: {
    fontSize: 10,
    color: colors.gray,
    fontWeight: '600',
  },
  modalTotalValue: {
    fontSize: 16,
    fontWeight: '900',
    color: colors.primary,
  },
  modalSubmitBtn: {
    backgroundColor: colors.primary,
    paddingHorizontal: 22,
    paddingVertical: 12,
    borderRadius: 22,
  },
  modalSubmitBtnDisabled: {
    opacity: 0.6,
  },
  modalSubmitText: {
    color: colors.white,
    fontSize: 13,
    fontWeight: '800',
  },
});
