import 'package:flutter/material.dart';
import 'package:flutter_restaurant/common/models/config_model.dart';
import 'package:flutter_restaurant/common/models/response_model.dart';
import 'package:flutter_restaurant/common/widgets/custom_image_widget.dart';
import 'package:flutter_restaurant/features/profile/domain/models/userinfo_model.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/features/auth/providers/auth_provider.dart';
import 'package:flutter_restaurant/features/profile/providers/profile_provider.dart';
import 'package:flutter_restaurant/features/splash/providers/splash_provider.dart';
import 'package:flutter_restaurant/utill/color_resources.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/styles.dart';
import 'package:flutter_restaurant/common/widgets/custom_button_widget.dart';
import 'package:flutter_restaurant/helper/custom_snackbar_helper.dart';
import 'package:flutter_restaurant/common/widgets/custom_text_field_widget.dart';
import 'package:flutter_restaurant/common/widgets/footer_widget.dart';
import 'package:flutter_restaurant/common/widgets/on_hover_widget.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import 'package:flutter_restaurant/utill/images.dart';

class ProfileWebWidgetOld extends StatefulWidget {
  final FocusNode? firstNameFocus;
  final FocusNode? lastNameFocus;
  final FocusNode? emailFocus;
  final FocusNode? phoneNumberFocus;
  final FocusNode? passwordFocus;
  final FocusNode? confirmPasswordFocus;
  final TextEditingController? firstNameController;
  final TextEditingController? lastNameController;
  final TextEditingController? emailController;
  final TextEditingController? phoneNumberController;
  final TextEditingController? passwordController;
  final TextEditingController? confirmPasswordController;

  final Function pickImage;
  final XFile? file;
  const ProfileWebWidgetOld({
    super.key,
    required this.firstNameFocus,
    required this.lastNameFocus,
    required this.emailFocus,
    required this.phoneNumberFocus,
    required this.passwordFocus,
    required this.confirmPasswordFocus,
    required this.firstNameController,
    required this.lastNameController,
    required this.emailController,
    required this.phoneNumberController,
    required this.passwordController,
    required this.confirmPasswordController,
    //function
    required this.pickImage,
    //file
    required this.file


  });

  @override
  State<ProfileWebWidgetOld> createState() => _ProfileWebWidgetOldState();
}

class _ProfileWebWidgetOldState extends State<ProfileWebWidgetOld> {
  @override
  void initState() {
    Provider.of<ProfileProvider>(context, listen: false).getUserInfo(true, isUpdate: true);
    super.initState();
  }
  @override
  Widget build(BuildContext context) {
    final ConfigModel configModel = Provider.of<SplashProvider>(context, listen: false).configModel!;
    return SingleChildScrollView(
      child: Column(
        children: [
          Consumer<ProfileProvider>(
            builder: (context, profileProvider, child) {
              return Center(
                child: SizedBox(
                  width: 1170,
                  child: Stack(
                    children: [
                      Column(
                        children: [
                          Container(
                            height: 150,  color:  ColorResources.getProfileMenuHeaderColor(context),
                            alignment: Alignment.centerLeft,
                            padding: const EdgeInsets.symmetric(horizontal: 240.0),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                profileProvider.userInfoModel != null ? Text(
                                  '${profileProvider.userInfoModel!.fName ?? ''} ${profileProvider.userInfoModel!.lName ?? ''}',
                                  style: robotoRegular.copyWith(fontSize: Dimensions.fontSizeExtraLarge, color: Theme.of(context).textTheme.bodyLarge?.color),
                                ) : const SizedBox(height: Dimensions.paddingSizeDefault, width: 150),
                                const SizedBox(height: Dimensions.paddingSizeSmall),
                                profileProvider.userInfoModel != null ? Text(
                                  profileProvider.userInfoModel!.email ?? '',
                                  style: robotoRegular.copyWith(color: Theme.of(context).textTheme.bodyLarge?.color),
                                ) : const SizedBox(height: 15, width: 100),

                                const SizedBox(height: Dimensions.paddingSizeSmall),

                                configModel.loyaltyPointStatus! && profileProvider.userInfoModel != null ? Text(
                                  '${getTranslated('points', context)}: ${profileProvider.userInfoModel!.point ?? ''}',
                                  style: rubikRegular.copyWith(color: Theme.of(context).textTheme.bodyLarge?.color),
                                ) : const SizedBox(),

                              ],
                            ),

                          ),
                          const SizedBox(height: 100),
                          Padding(
                            padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault),
                            child: Padding(
                              padding: const EdgeInsets.only(left: 240.0),
                              child: Column(
                                children: [
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                    children: [
                                      Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            getTranslated('first_name', context)!,
                                            style: Theme.of(context).textTheme.displayMedium!.copyWith(color: ColorResources.getHintColor(context), fontWeight: FontWeight.w400, fontSize: Dimensions.fontSizeSmall),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeSmall),
                                          SizedBox(
                                            width: 430,
                                            child: CustomTextFieldWidget(
                                              hintText: 'John',
                                              isShowBorder: true,
                                              controller: widget.firstNameController,
                                              focusNode: widget.firstNameFocus,
                                              nextFocus: widget.lastNameFocus,
                                              inputType: TextInputType.name,
                                              capitalization: TextCapitalization.words,
                                            ),
                                          ),

                                          const SizedBox(height: Dimensions.paddingSizeLarge),

                                          // for email section
                                          Text(
                                            getTranslated('email', context)!,
                                            style: Theme.of(context).textTheme.displayMedium!.copyWith(color: ColorResources.getHintColor(context), fontWeight: FontWeight.w400, fontSize: Dimensions.fontSizeSmall),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeSmall),
                                          SizedBox(
                                            width: 430,
                                            child: CustomTextFieldWidget(
                                              hintText: getTranslated('demo_gmail', context),
                                              isShowBorder: true,
                                              controller: widget.emailController,
                                              isEnabled: false,
                                              focusNode: widget.emailFocus,
                                              nextFocus: widget.phoneNumberFocus,

                                              inputType: TextInputType.emailAddress,
                                            ),
                                          ),

                                          const SizedBox(height: Dimensions.paddingSizeLarge),

                                          Text(
                                            getTranslated('password', context)!,
                                            style: Theme.of(context).textTheme.displayMedium!.copyWith(color: ColorResources.getHintColor(context), fontWeight: FontWeight.w400, fontSize: Dimensions.fontSizeSmall),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeSmall),
                                          SizedBox(
                                            width: 430,
                                            child: CustomTextFieldWidget(
                                              hintText: getTranslated('password_hint', context),
                                              isShowBorder: true,
                                              controller: widget.passwordController,
                                              focusNode: widget.passwordFocus,
                                              nextFocus: widget.confirmPasswordFocus,
                                              isPassword: true,
                                              isShowSuffixIcon: true,
                                            ),
                                          ),

                                          const SizedBox(height: Dimensions.paddingSizeLarge),



                                        ],
                                      ),
                                      const SizedBox(height: Dimensions.paddingSizeDefault),
                                      Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            getTranslated('last_name', context)!,
                                            style: Theme.of(context).textTheme.displayMedium!.copyWith(color: ColorResources.getHintColor(context), fontWeight: FontWeight.w400, fontSize: Dimensions.fontSizeSmall),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeSmall),
                                          SizedBox(
                                            width: 430,
                                            child: CustomTextFieldWidget(
                                              hintText: 'Doe',
                                              isShowBorder: true,
                                              controller: widget.lastNameController,
                                              focusNode: widget.lastNameFocus,
                                              nextFocus: widget.phoneNumberFocus,
                                              inputType: TextInputType.name,
                                              capitalization: TextCapitalization.words,
                                            ),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeLarge),

                                          // for phone Number section
                                          Text(
                                            getTranslated('mobile_number', context)!,
                                            style: Theme.of(context).textTheme.displayMedium!.copyWith(color: ColorResources.getHintColor(context), fontWeight: FontWeight.w400, fontSize: Dimensions.fontSizeSmall),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeSmall),

                                          SizedBox(
                                            width: 430,
                                            child: CustomTextFieldWidget(
                                              hintText: getTranslated('number_hint', context),
                                              isShowBorder: true,
                                              controller: widget.phoneNumberController,
                                              focusNode: widget.phoneNumberFocus,
                                              nextFocus: widget.passwordFocus,
                                              inputType: TextInputType.phone,
                                            ),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeLarge),

                                          Text(
                                            getTranslated('confirm_password', context)!,
                                            style: Theme.of(context).textTheme.displayMedium!.copyWith(color: ColorResources.getHintColor(context), fontWeight: FontWeight.w400, fontSize: Dimensions.fontSizeSmall),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeSmall),
                                          SizedBox(
                                            width: 430,
                                            child: CustomTextFieldWidget(
                                              hintText: getTranslated('password_hint', context),
                                              isShowBorder: true,
                                              controller: widget.confirmPasswordController,
                                              focusNode: widget.confirmPasswordFocus,
                                              isPassword: true,
                                              isShowSuffixIcon: true,
                                              inputAction: TextInputAction.done,
                                            ),
                                          ),
                                          const SizedBox(height: Dimensions.paddingSizeLarge),





                                          ],
                                      ),
                                      const SizedBox(height: 55.0)
                                    ],
                                  ),
                                  SizedBox(
                                    width: 180.0,
                                    child: CustomButtonWidget(
                                      btnTxt: getTranslated('update_profile', context),
                                      onTap: () async {
                                        String firstName = widget.firstNameController!.text.trim();
                                        String lastName = widget.lastNameController!.text.trim();
                                        String phoneNumber = widget.phoneNumberController!.text.trim();
                                        String password = widget.passwordController!.text.trim();
                                        String confirmPassword = widget.confirmPasswordController!.text.trim();
                                        String email = widget.emailController!.text.trim();

                                        if (profileProvider.userInfoModel!.fName == firstName &&
                                            profileProvider.userInfoModel!.lName == lastName &&
                                            profileProvider.userInfoModel!.phone == phoneNumber &&
                                            profileProvider.userInfoModel!.email == email && widget.file == null
                                            && password.isEmpty && confirmPassword.isEmpty) {
                                          showCustomSnackBarHelper(getTranslated('change_something_to_update', context));
                                        }else if (firstName.isEmpty) {
                                          showCustomSnackBarHelper(getTranslated('enter_first_name', context));
                                        }else if (lastName.isEmpty) {
                                          showCustomSnackBarHelper(getTranslated('enter_last_name', context));
                                        }else if (phoneNumber.isEmpty) {
                                          showCustomSnackBarHelper(getTranslated('enter_phone_number', context));
                                        } else if((password.isNotEmpty && password.length < 6)
                                            || (confirmPassword.isNotEmpty && confirmPassword.length < 6)) {
                                          showCustomSnackBarHelper(getTranslated('password_should_be', context));
                                        } else if(password != confirmPassword) {
                                          showCustomSnackBarHelper(getTranslated('password_did_not_match', context));
                                        } else {
                                          UserInfoModel updateUserInfoModel = UserInfoModel();
                                          updateUserInfoModel.fName = firstName;
                                          updateUserInfoModel.lName = lastName;
                                          updateUserInfoModel.phone = phoneNumber;
                                          updateUserInfoModel.email = email;
                                          String pass = password;

                                          ResponseModel responseModel = await profileProvider.updateUserInfo(
                                            updateUserInfoModel, pass, null, widget.file,
                                            Provider.of<AuthProvider>(context, listen: false).getUserToken(),
                                          );

                                          if(responseModel.isSuccess) {
                                            profileProvider.getUserInfo(true);
                                            if(context.mounted){
                                              showCustomSnackBarHelper(getTranslated('updated_successfully', context), isError: false);
                                            }
                                          }else {
                                            showCustomSnackBarHelper(responseModel.message);
                                          }
                                          setState(() {});
                                        }
                                      },
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),
                      Positioned(
                        left: 30,
                        top: 45,
                        child: Stack(
                          children: [
                            Container(
                              height: 180, width: 180,
                              decoration: BoxDecoration(shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 4),
                                  boxShadow: [BoxShadow(color: Colors.white.withValues(alpha:0.1), blurRadius: 22, offset: const Offset(0, 8.8) )]),
                              child: ClipOval(
                                child: widget.file == null ?
                                CustomImageWidget(
                                  placeholder: Images.placeholderUser, height: 170, width: 170, fit: BoxFit.cover,
                                  image:  '${Provider.of<SplashProvider>(context, listen: false).baseUrls!.customerImageUrl}/'
                                      '${profileProvider.userInfoModel != null ? profileProvider.userInfoModel!.image : ''}',
                                  //imageErrorBuilder: (c, o, s) => Image.asset(Images.placeholderUser, height: 170, width: 170, fit: BoxFit.cover),
                                ) : Image.network(widget.file!.path, height: 170.0, width: 170.0, fit: BoxFit.cover),
                              ),
                            ),
                            Positioned(
                                bottom: 10,
                                right: 10,
                                child: OnHoverWidget(
                                  builder: (isHover) {
                                    return InkWell(
                                      hoverColor: Colors.transparent,
                                      onTap: widget.pickImage as void Function()?,
                                      child: Container(
                                        padding: const EdgeInsets.all(10),
                                        decoration: BoxDecoration(
                                          color: Theme.of(context).primaryColor.withValues(alpha:0.5),
                                          shape: BoxShape.circle,
                                        ),
                                        child: const Icon(Icons.camera_alt,color: Colors.white60),),);
                                  }
                                ))
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              );
            }
          ),
          const SizedBox(height: 55),

          const FooterWidget(),
        ],
      ),
    );
  }
}
