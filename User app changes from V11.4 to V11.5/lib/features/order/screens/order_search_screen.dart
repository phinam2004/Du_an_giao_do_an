import 'package:country_code_picker/country_code_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_restaurant/helper/responsive_helper.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/features/order/providers/order_provider.dart';
import 'package:flutter_restaurant/features/splash/providers/splash_provider.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/images.dart';
import 'package:flutter_restaurant/helper/router_helper.dart';
import 'package:flutter_restaurant/utill/styles.dart';
import 'package:flutter_restaurant/common/widgets/custom_app_bar_widget.dart';
import 'package:flutter_restaurant/common/widgets/custom_button_widget.dart';
import 'package:flutter_restaurant/helper/custom_snackbar_helper.dart';
import 'package:flutter_restaurant/common/widgets/custom_text_field_widget.dart';
import 'package:flutter_restaurant/common/widgets/footer_widget.dart';
import 'package:flutter_restaurant/common/widgets/web_app_bar_widget.dart';
import 'package:flutter_restaurant/common/widgets/code_picker_widget.dart';
import 'package:provider/provider.dart';

class OrderSearchScreen extends StatefulWidget {
  const OrderSearchScreen({super.key});

  @override
  State<OrderSearchScreen> createState() => _OrderSearchScreenState();
}

class _OrderSearchScreenState extends State<OrderSearchScreen> {
  final TextEditingController orderIdTextController = TextEditingController();
  final TextEditingController phoneNumberTextController = TextEditingController();
  final FocusNode orderIdFocusNode = FocusNode();
  final FocusNode phoneFocusNode = FocusNode();
  String? countryCode;

  @override
  void initState() {
    countryCode = CountryCode.fromCountryCode(Provider.of<SplashProvider>(context, listen: false).configModel!.countryCode!).code;
    super.initState();
  }


  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: ResponsiveHelper.isDesktop(context) ? const PreferredSize(
        preferredSize: Size.fromHeight(100), child: WebAppBarWidget(),
      ) : CustomAppBarWidget(
        title: getTranslated('order_details', context)!,
        centerTitle: false,
        actionView: TrackRefreshButtonView(
          orderIdTextController: orderIdTextController,
          phoneNumberTextController: phoneNumberTextController,
        ),
      ) as PreferredSizeWidget,

      body: CustomScrollView(slivers: [

        SliverToBoxAdapter(child: Container(
          margin: ResponsiveHelper.isDesktop(context) ? EdgeInsets.symmetric(horizontal: (MediaQuery.sizeOf(context).width - Dimensions.webScreenWidth) / 2) : null,
          decoration: ResponsiveHelper.isDesktop(context) ? BoxDecoration(
            color: Theme.of(context).canvasColor, borderRadius: BorderRadius.circular(Dimensions.radiusDefault),
            boxShadow: [BoxShadow(color: Theme.of(context).shadowColor, blurRadius: 5, spreadRadius: 1)],
          ) : null,
          child: Column(children: [
            if(ResponsiveHelper.isDesktop(context)) Center(child: Container(
              padding: const EdgeInsets.only(top: Dimensions.paddingSizeLarge),
              width: Dimensions.webScreenWidth,
              child: Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, crossAxisAlignment: CrossAxisAlignment.center, children: [
                const SizedBox(),
                Text(getTranslated('order_details', context)!, style: rubikBold.copyWith(fontSize: Dimensions.fontSizeOverLarge)),

                TrackRefreshButtonView(
                  orderIdTextController: orderIdTextController,
                  phoneNumberTextController: phoneNumberTextController,
                ),


              ]),
            )),

            Container(
              padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault),
              child: Column(children: [

                Padding(
                  padding: ResponsiveHelper.isDesktop(context) ? const EdgeInsets.symmetric(
                    horizontal: Dimensions.paddingSizeLarge, vertical: Dimensions.paddingSizeSmall,
                  ) : const EdgeInsets.only(top: Dimensions.paddingSizeSmall),
                  child: InputView(
                    orderIdTextController: orderIdTextController, orderIdFocusNode: orderIdFocusNode,
                    phoneFocusNode: phoneFocusNode, phoneNumberTextController: phoneNumberTextController,
                    countryCode: countryCode,
                    onValueChange: (String code) {
                      setState(() {
                        countryCode = code;
                      });
                    },
                  ),
                ),

                Column(children: [
                  const SizedBox(height: Dimensions.paddingSizeLarge),
                  Image.asset(Images.outForDelivery, color: Theme.of(context).disabledColor.withValues(alpha:0.5), width:  70),
                  const SizedBox(height: Dimensions.paddingSizeDefault),

                  Text(getTranslated('enter_your_order_id', context)!, style: rubikRegular.copyWith(
                    color: Theme.of(context).disabledColor,
                  ), maxLines: 2,  textAlign: TextAlign.center),
                  const SizedBox(height: 100),

                ]),
              ]),
            ),

          ]),
        )),

        if(ResponsiveHelper.isDesktop(context))  const SliverFillRemaining(
          hasScrollBody: false,
          child: Column(mainAxisAlignment: MainAxisAlignment.end, children: [
            SizedBox(height: Dimensions.paddingSizeLarge),

            FooterWidget(),
          ]),
        ),
      ]),
    );
  }
}

class TrackRefreshButtonView extends StatelessWidget {
  const TrackRefreshButtonView({
    super.key,
    required this.orderIdTextController,
    required this.phoneNumberTextController,
  });

  final TextEditingController orderIdTextController;
  final TextEditingController phoneNumberTextController;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault),
      child: ElevatedButton.icon(
        style: ElevatedButton.styleFrom(
          elevation: 0,
          backgroundColor: ResponsiveHelper.isDesktop(context)
              ? Theme.of(context).canvasColor
              : Theme.of(context).cardColor,
          padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault),
        ),
        onPressed: () {
          orderIdTextController.clear();
          phoneNumberTextController.clear();
          Provider.of<OrderProvider>(context, listen: false).clearPrevData(isUpdate: true);
        },
        icon: Icon(Icons.refresh,color: Theme.of(context).primaryColor),
        label: Text(getTranslated('refresh', context)!, style: rubikSemiBold.copyWith(
          color: Theme.of(context).textTheme.bodyLarge?.color,
        )),
      ),
    );
  }
}


class InputView extends StatelessWidget {
  const InputView({
    super.key,
    required this.orderIdTextController,
    required this.orderIdFocusNode,
    required this.phoneFocusNode,
    required this.phoneNumberTextController,
    required this.countryCode,
    required this.onValueChange,
  });

  final TextEditingController orderIdTextController;
  final FocusNode orderIdFocusNode;
  final FocusNode phoneFocusNode;
  final TextEditingController phoneNumberTextController;
  final String? countryCode;
  final Function(String value) onValueChange;

  @override
  Widget build(BuildContext context) {

    return !ResponsiveHelper.isDesktop(context) ? Column(children: [
      FormField(builder: (builder)=> Column(children: [
        OrderIdTextField(
          orderIdTextController: orderIdTextController,
          orderIdFocusNode: orderIdFocusNode,
          phoneFocusNode: phoneFocusNode,
        ),
        const SizedBox(height: Dimensions.paddingSizeLarge),

        PhoneNumberFieldView(
          onValueChange: onValueChange,
          countryCode: countryCode,
          phoneNumberTextController: phoneNumberTextController,
          phoneFocusNode: phoneFocusNode,
        ),
        const SizedBox(height: Dimensions.paddingSizeLarge),

      ])),
      const SizedBox(height: Dimensions.paddingSizeDefault),

      TrackOrderButtonView(
        orderIdTextController: orderIdTextController,
        countryCode: countryCode,
        phoneNumberTextController: phoneNumberTextController,
      ),
    ]) : Center(child: SizedBox(
      width: Dimensions.webScreenWidth,
      child: FormField(builder: (builder)=> Row(children: [
        Expanded(child: OrderIdTextField(
          orderIdTextController: orderIdTextController,
          orderIdFocusNode: orderIdFocusNode,
          phoneFocusNode: phoneFocusNode,
        )),
        const SizedBox(width: Dimensions.paddingSizeLarge),

        Expanded(child: PhoneNumberFieldView(
          onValueChange: onValueChange, countryCode: countryCode,
          phoneNumberTextController: phoneNumberTextController,
          phoneFocusNode: phoneFocusNode,
        )),
        const SizedBox(width: Dimensions.paddingSizeLarge),


        Expanded(child: TrackOrderButtonView(
          orderIdTextController: orderIdTextController,
          countryCode: countryCode,
          phoneNumberTextController: phoneNumberTextController,
        )),
      ])),
    ));
  }
}



class TrackOrderButtonView extends StatelessWidget {
  const TrackOrderButtonView({
    super.key,
    required this.orderIdTextController,
    required this.countryCode,
    required this.phoneNumberTextController,
  });

  final TextEditingController orderIdTextController;
  final String? countryCode;
  final TextEditingController phoneNumberTextController;

  @override
  Widget build(BuildContext context) {
    return Selector<OrderProvider, bool>(
      selector: (_, orderProvider) => orderProvider.isLoading,
      builder: (context, isLoading, child) {
        return CustomButtonWidget(
          isLoading: isLoading,
          borderRadius: ResponsiveHelper.isDesktop(context) ? Dimensions.radiusDefault : Dimensions.radiusExtraLarge,
          btnTxt: getTranslated('order_details', context),
          onTap: () async {
            final String orderId = orderIdTextController.text.trim();
            final int? checkOrderId = int.tryParse(orderId);

            final String phoneNumber = '${CountryCode.fromCountryCode(countryCode!).dialCode}${phoneNumberTextController.text.trim()}';
            if(orderId.isEmpty){
              showCustomSnackBarHelper(getTranslated('enter_order_id', context));
            }else if(checkOrderId == null){
              showCustomSnackBarHelper(getTranslated('enter_valid_order_id', context));

            }else if(phoneNumberTextController.text.isEmpty){
              showCustomSnackBarHelper(getTranslated('enter_phone_number', context));
            }else {
              final orderList = await Provider.of<OrderProvider>(
                  context, listen: false).getOrderDetails(
                orderId,
                phoneNumber: phoneNumber,
                isUpdate: true,
              );

              if(orderList?.isNotEmpty ?? false) {
                RouterHelper.getOrderDetailsRoute(orderId, phoneNumber: phoneNumber);
              }
            }

          },
        );
      }
    );
  }
}

class PhoneNumberFieldView extends StatelessWidget {
  const PhoneNumberFieldView({
    super.key,
    required this.onValueChange,
    required this.countryCode,
    required this.phoneNumberTextController,
    required this.phoneFocusNode,
  });

  final Function(String value) onValueChange;
  final String? countryCode;
  final TextEditingController phoneNumberTextController;
  final FocusNode phoneFocusNode;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.only(left: Dimensions.paddingSizeSmall),
      decoration: BoxDecoration(

          color: Theme.of(context).cardColor,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: Theme.of(context).primaryColor.withValues(alpha:0.2))
      ),
      child: Row(children: [
        CodePickerWidget(
          onChanged: (CountryCode value)=> onValueChange(value.code!),
          initialSelection: countryCode,
          favorite: [countryCode ?? ''],
          showDropDownButton: true,
          padding: EdgeInsets.zero,
          showFlagMain: true,
          textStyle: TextStyle(color: Theme.of(context).textTheme.displayLarge!.color),

        ),
        Expanded(child: CustomTextFieldWidget(
          controller: phoneNumberTextController,
          focusNode: phoneFocusNode,
          inputType: TextInputType.phone,
          hintText: getTranslated('number_hint', context),

        )),
      ]),
    );
  }
}

class OrderIdTextField extends StatelessWidget {
  const OrderIdTextField({
    super.key,
    required this.orderIdTextController,
    required this.orderIdFocusNode,
    required this.phoneFocusNode,
  });

  final TextEditingController orderIdTextController;
  final FocusNode orderIdFocusNode;
  final FocusNode phoneFocusNode;

  @override
  Widget build(BuildContext context) {
    return CustomTextFieldWidget(
      controller: orderIdTextController,
      focusNode: orderIdFocusNode,
      nextFocus: phoneFocusNode,
      isShowBorder: true,
      hintText: getTranslated('order_id', context),
      prefixIconUrl: Images.order,
      isShowPrefixIcon: true,
      suffixIconUrl: Images.order,
      inputType: TextInputType.number,

    );
  }
}