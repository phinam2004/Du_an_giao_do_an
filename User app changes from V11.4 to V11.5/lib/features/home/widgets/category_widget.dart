import 'package:flutter/material.dart';
import 'package:flutter_restaurant/common/widgets/custom_image_widget.dart';
import 'package:flutter_restaurant/helper/responsive_helper.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/features/category/providers/category_provider.dart';
import 'package:flutter_restaurant/features/splash/providers/splash_provider.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/images.dart';
import 'package:flutter_restaurant/helper/router_helper.dart';
import 'package:flutter_restaurant/utill/styles.dart';
import 'package:flutter_restaurant/common/widgets/title_widget.dart';
import 'package:flutter_restaurant/features/home/widgets/category_pop_up_widget.dart';
import 'package:provider/provider.dart';
import 'package:shimmer_animation/shimmer_animation.dart';

class CategoryWidget extends StatelessWidget {
  const CategoryWidget({super.key});

  @override
  Widget build(BuildContext context) {

    return Consumer<CategoryProvider>(
      builder: (context, category, child) {
        return Column(
          children: [
            Padding(
              padding: const EdgeInsets.fromLTRB(10, 20, 0, 10),
              child: TitleWidget(title: getTranslated('all_categories', context)),
            ),
            Row(
              children: [
                Expanded(
                  child: SizedBox(
                    height: 80,
                    child: category.categoryList != null ? category.categoryList!.isNotEmpty ? ListView.builder(
                      itemCount: category.categoryList!.length,
                      padding: const EdgeInsets.only(left: Dimensions.paddingSizeSmall),
                      physics: const BouncingScrollPhysics(),
                      scrollDirection: Axis.horizontal,
                      itemBuilder: (context, index) {
                        String? name = '';
                        category.categoryList![index].name!.length > 15  ? name = '${category.categoryList![index].name!.substring(0, 15)} ...' : name = category.categoryList![index].name;
                        return Padding(
                          padding: const EdgeInsets.only(right: Dimensions.paddingSizeSmall),
                          child: InkWell(
                            onTap: () => RouterHelper.getCategoryRoute(category.categoryList![index]),
                            child: Column(children: [
                              ClipOval(
                                child: CustomImageWidget(
                                  placeholder: Images.placeholderImage, width: 65, height: 65, fit: BoxFit.cover,
                                  image: Provider.of<SplashProvider>(context, listen: false).baseUrls != null
                                      ? '${Provider.of<SplashProvider>(context, listen: false).baseUrls!.categoryImageUrl}/${category.categoryList![index].image}':'',
                                  // width: 100, height: 100, fit: BoxFit.cover,
                                ),
                              ),

                              Flexible(
                                child: Text(
                                  name!,
                                  style: rubikSemiBold.copyWith(fontSize: Dimensions.fontSizeSmall),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),

                            ]),
                          ),
                        );
                      },
                    ) : Center(child: Text(getTranslated('no_category_available', context)!)) : const CategoryShimmer(),
                  ),
                ),
               ResponsiveHelper.isMobile()? const SizedBox(): category.categoryList != null ? Column(
                  children: [
                    InkWell(
                      onTap: (){
                        showDialog(context: context, builder: (con) => const Dialog(
                          child: SizedBox(height: 550, width: 600, child: CategoryPopUpWidget())
                        ));
                      },
                      child: Padding(
                        padding: const EdgeInsets.only(right: Dimensions.paddingSizeSmall),
                        child: CircleAvatar(
                          radius: 35,
                          backgroundColor: Theme.of(context).primaryColor,
                          child: Text(getTranslated('view_all', context)!, style: const TextStyle(fontSize: 14,color: Colors.white)),
                        ),
                      ),
                    ),
                    const SizedBox(height: 10,)
                  ],
                ): const CategoryAllShimmer()
              ],
            ),

          ],
        );
      },
    );
  }
}

class CategoryShimmer extends StatelessWidget {
  const CategoryShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 80,
      child: ListView.builder(
        itemCount: 14,
        padding: const EdgeInsets.only(left: Dimensions.paddingSizeSmall),
        physics: const BouncingScrollPhysics(),
        shrinkWrap: true,
        scrollDirection: Axis.horizontal,
        itemBuilder: (context, index) {
          return Padding(
            padding: const EdgeInsets.only(right: Dimensions.paddingSizeSmall),
            child: Shimmer(
              duration: const Duration(seconds: 2),
              enabled: Provider.of<CategoryProvider>(context).categoryList == null,
              child: Column(children: [
                Container(
                  height: 65, width: 65, 
                  decoration: BoxDecoration(
                    color: Theme.of(context).shadowColor,
                    shape: BoxShape.circle,
                  ),
                ),
                const SizedBox(height: 5),
                Container(height: 10, width: 50, color: Theme.of(context).shadowColor),
              ]),
            ),
          );
        },
      ),
    );
  }
}

class CategoryAllShimmer extends StatelessWidget {
  const CategoryAllShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 80,
      child: Padding(
        padding: const EdgeInsets.only(right: Dimensions.paddingSizeSmall),
        child: Shimmer(
          duration: const Duration(seconds: 2),
          enabled: Provider.of<CategoryProvider>(context).categoryList == null,
          child: Column(children: [
            Container(
              height: 65, width: 65,
              decoration: BoxDecoration(
                color: Theme.of(context).shadowColor,
                shape: BoxShape.circle,
              ),
            ),
            const SizedBox(height: 5),
            Container(height: 10, width: 50, color: Theme.of(context).shadowColor),
          ]),
        ),
      ),
    );
  }
}

