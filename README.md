# forgot.password
1C-Bitrix component forgot.password

# Код вызова компонента:

$APPLICATION->IncludeComponent(
    "flxmd:forgot.password",
    "forgot_modal",
    array(
        "COMPONENT_TEMPLATE" => "forgot_modal",
    ),
    false
);
