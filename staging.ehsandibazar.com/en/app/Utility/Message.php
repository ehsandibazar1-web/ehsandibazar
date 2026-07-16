<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 12/24/2018
 * Time: 01:52 PM
 */

namespace App\Utility;


class Message
{


    /* success */
    const successMessageCreate = "آیتم مورد نظر به درستی ایجاد شد";
    const successMessageEdit = "آیتم مورد نظر به درستی ویرایش شد";
    const successMessageDelete = "آیتم مورد نظر به درستی حذف شد";

    const userNotAvailable = "کاربری یافت نشد";

    const successMassageFavoriteDelete = "محصول مورد نظر از علاقه مندی شما حذف گردید";

    /* error */
    const errorMessageEdit = "خطا در ویرایش آیتم مورد نظر";
    const errorMessageDelete = "خطا در حذف آیتم مورد نظر";
    const errorMessageLogin = "شما مجوز ورود به این صفحه را ندارید";
    const errorMessageCreate = "خطا در ایجاد آیتم";

    /* System */
    const systemError = "خطای سیستمی";


    /* site */
    const errorMessageSiteSendEmail = "نظر شما در سامانه ثبت نشد , لطفا چند لحظه دیگر امتحان فرمایید , با تشکر";
    const successMessageSiteSendEmail = "نظر شما در سامانه ثبت شد , با تشکر";
    const illegalError  = "اطلاعات غیر مجاز می باشد , لطفا اطلاعات مناسب را وارد نمایید";
    const SuccessMessageContact = "پیغام شما با موفقیت در سامانه ذخیره شد.";
    const ErrorMessageContact = "خطا در ثبت پیغام شما";


    /* email */
    const successSendEmailPanel = "ایمیل شما با موفقیت ارسال شد ";
    const errorSendEmailPanel = "خطایی در ارسال ایمیل وجود دارد , لطفا بعدا امتحان فرمایید";

    /* sms */
    /*const successSendSmsPanel = "پیامک شما با موفقیت ارسال شد ";
    const errorSendSmsPanel = "خطایی در ارسال پیامک وجود دارد , لطفا بعدا امتحان فرمایید";*/

    /*============================== image ===================================*/
    /* invalid image */
    const inValidImage = "لطفا فرمت انتخابی از فرمت های jpg , jpeg , png باشد.";

    /* not fount image */
    const notFoundImage = "تصویر مورد نظر یافت نشد";

    /* not correctly */
    const imageNotCorrectly = "لطفا تصویر مناسب را وارد نمایید.";

    /*================================ video ===================================*/


    /* invalid image */
    const inValidVideo = "لطفا فرمت انتخابی فرمت mp4 باشد.";

    /* not fount image */
    const notFoundVideo = "ویدیوی مورد نظر یافت نشد";

    /* not correctly */
    const videoNotCorrectly = "لطفا ویدیوی مناسب را وارد نمایید.";



    /* attribute type value details */
    const fillInput = "لطفا فیلد های مفدار را پر نمایید.";

    /* price */
    const price = "لطفا مقدار قیمت را وارد نمایید.";

    /* count */
    const count = "لطفا تعداد را وارد نمایید.";

    /* details */
    const Details = "لطفا مقادیر خواسته شده در بخش جزییات محصول را به درستی وارد نمایید.";



    /* dashboard  */
    // -- score
    const score = "امتیاز شما کمتر از میزان هدیه می باشد.";
    const score_admin = "امتیاز کاربر کمتر از میزان هدیه می باشد.";

    const request = "درخواست شما ثبت شد";

    const incrementScoreUser = "امتیاز هدیه از کاربر کم شد.";

    const requestAgain = "شما این هدیه را قبلا در خواست داده اید.";



}
