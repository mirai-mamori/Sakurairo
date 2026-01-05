// js/bilibili-uid-filler.js
// B站UID自动填充功能 - ES模块版本
class BilibiliUIDFiller {
    constructor() {
        this.lastUid = "";
        this.originalAvatarSrc = null;
        this.cacheKeyPrefix = "bilibili_user_";
        this.cacheExpiryDays = 7;
        this.currentUserData = null; // 保存当前用户数据
        this.initialize();
    }

    initialize() {
        document.addEventListener("DOMContentLoaded", () => this.setupUI());
        this.addStyles();
    }

    setupUI() {
        const commentForm = document.getElementById("commentform");
        if (!commentForm) return;

        // 检查是否已经存在B站UID输入框
        if (document.getElementById("bilibili_uid")) {
            console.log("B站UID输入框已存在，跳过添加");
            return;
        }

        this.addUIDInput();

        // 页面加载时检查缓存
        this.checkCacheAndSetupUI();
    }

    addUIDInput() {
        const authorField = document.querySelector(
            ".cmt-info-container .cmt-author"
        );
        if (!authorField) return;

        // 查找邮箱输入框，在它前面插入
        const emailField = document.querySelector(
            '.cmt-info-container input[type="text"][name="email"]'
        );
        const targetField = emailField ? emailField.closest(".popup") : null;

        const biliUIDHtml = this.createUIDInputHTML();

        if (targetField) {
            targetField.insertAdjacentHTML("beforebegin", biliUIDHtml);
        } else {
            authorField.insertAdjacentHTML("afterend", biliUIDHtml);
        }

        this.bindUIDEvents();

        // 监听评论表单提交，保存用户信息
        this.bindFormSubmitEvent();
    }

    createUIDInputHTML() {
        return `
            <div class="popup cmt-popup bilibili-popup" style="position:relative;">
                <input type="text" placeholder="B站UID" name="bilibili_uid" id="bilibili_uid" 
                    size="22" autocomplete="off" tabindex="1" style="margin-bottom: 5px;">
                <select id="bilibili_uid_select" style="
                    display: none; 
                    width: 100%; 
                    padding: 8px 10px;
                    margin-bottom: 5px;
                    font-size: 14px;
                    line-height: 1.4;
                    color: #333;
                    background-color: #fff;
                    background-image: none;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                    appearance: none;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    cursor: pointer;
                ">
                    <option value="">选择已保存的B站账号...</option>
                </select>
                <span class="popuptext" style="margin-left: -85px;width: 170px;">
                    输入B站UID自动拉取昵称和头像
                </span>
                <div id="bili_loading" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);display:none;">
                    <i class="fa-solid fa-spinner fa-spin" style="font-size:12px;"></i>
                </div>
            </div>
        `;
    }

    bindUIDEvents() {
        const uidInput = document.getElementById("bilibili_uid");
        const uidSelect = document.getElementById("bilibili_uid_select");

        if (!uidInput) return;

        uidInput.addEventListener("blur", (e) => this.handleUIDChange(e));
        uidInput.addEventListener("keypress", (e) => this.handleUIDKeyPress(e));

        if (uidSelect) {
            uidSelect.addEventListener("change", (e) => this.handleSelectChange(e));
        }
    }

    bindFormSubmitEvent() {
        const commentForm = document.getElementById("commentform");
        if (!commentForm) return;

        commentForm.addEventListener("submit", (e) => {
            // 提交表单时检查是否需要保存用户信息
            this.checkAndSaveUser();
        });
    }

    // 检查是否需要保存用户信息
    checkAndSaveUser() {
        // 检查是否填写了B站UID
        const uidInput = document.getElementById("bilibili_uid");
        const authorInput = document.getElementById("author");

        if (
            !uidInput ||
            !uidInput.value.trim() ||
            !authorInput ||
            !authorInput.value.trim()
        ) {
            return; // 没有填写完整信息，不保存
        }

        // 检查是否来自B站
        if (authorInput.getAttribute("data-from-bilibili") !== "true") {
            return; // 不是来自B站的用户，不保存
        }

        // 检查是否勾选了"记住我"选项
        const rememberMe = document.getElementById("wp-comment-cookies-consent");
        if (!rememberMe || !rememberMe.checked) {
            // 没勾选"记住我"，不保存并清理可能的缓存
            this.cleanupUnwantedCache();
            return;
        }

        // 检查当前填写的UID是否与上次查询的一致
        const uid = uidInput.value.trim();
        if (uid !== this.lastUid) {
            return; // UID不一致，不保存
        }

        // 保存用户信息
        this.saveUserToStorage(uid);
    }

    // 保存用户信息到本地存储
    saveUserToStorage(uid) {
        const authorInput = document.getElementById("author");
        const avatarField = document.getElementById("bilibili_avatar_hidden");
        const levelField = document.getElementById("bilibili_level_hidden");

        if (!authorInput || !avatarField) return;

        // 构建用户数据
        const userData = {
            name: authorInput.value,
            face: this.processAvatarUrl(avatarField.value || ""),
            level: levelField ? levelField.value : "0",
        };

        // 保存到本地存储
        const cacheData = {
            uid: uid,
            data: userData,
            timestamp: Date.now(),
        };

        localStorage.setItem(this.cacheKeyPrefix + uid, JSON.stringify(cacheData));
        console.log("用户信息已保存到本地存储:", cacheData);

        // 显示保存成功的提示
        this.showToast("B站账号信息已保存", "success");
    }

    // 清理不需要的缓存
    cleanupUnwantedCache() {
        const uidInput = document.getElementById("bilibili_uid");
        if (uidInput && uidInput.value) {
            const uid = uidInput.value.trim();
            const cacheKey = this.cacheKeyPrefix + uid;
            localStorage.removeItem(cacheKey);
        }
    }

    // 检查缓存并设置UI
    async checkCacheAndSetupUI() {
        const cachedUsers = this.getAllCachedUsers();

        if (cachedUsers.length === 0) {
            // 没有缓存，保持原样
            console.log("未检测到缓存的B站账号");
            return;
        } else if (cachedUsers.length === 1) {
            // 只有一个缓存账号，自动填充
            console.log("检测到1个缓存的B站账号");
            this.showToast(
                `检测到上次使用的B站账号: ${cachedUsers[0].data.name}`,
                "info"
            );

            // 延迟一下，确保UI完全加载
            setTimeout(() => {
                this.fillFromCache(cachedUsers[0].uid, true);
            }, 500);
        } else {
            // 多个缓存账号，显示下拉选择
            console.log(`检测到${cachedUsers.length}个缓存的B站账号`);
            this.showMultipleAccountsUI(cachedUsers);
        }
    }

    // 获取所有缓存的用户
    getAllCachedUsers() {
        try {
            const keys = Object.keys(localStorage);
            const cachedUsers = [];

            for (const key of keys) {
                if (key.startsWith(this.cacheKeyPrefix)) {
                    try {
                        const cacheData = JSON.parse(localStorage.getItem(key));

                        if (cacheData && this.isCacheValid(cacheData.timestamp)) {
                            cachedUsers.push(cacheData);
                        } else {
                            // 缓存无效，清理
                            localStorage.removeItem(key);
                        }
                    } catch (e) {
                        console.error("解析缓存数据失败:", e);
                        localStorage.removeItem(key);
                    }
                }
            }

            // 按时间倒序排序
            return cachedUsers.sort((a, b) => b.timestamp - a.timestamp);
        } catch (e) {
            console.error("读取缓存失败:", e);
            return [];
        }
    }

    // 显示多账号选择UI
    showMultipleAccountsUI(cachedUsers) {
        const uidInput = document.getElementById("bilibili_uid");
        const uidSelect = document.getElementById("bilibili_uid_select");

        if (!uidInput || !uidSelect) return;

        // 隐藏输入框，显示选择框
        uidInput.style.display = "none";
        uidSelect.style.display = "block";

        // 清空选项
        uidSelect.innerHTML = '<option value="">选择已保存的B站账号...</option>';

        // 添加缓存账号选项
        cachedUsers.forEach((user, index) => {
            const option = document.createElement("option");
            option.value = user.uid;
            option.textContent = `${user.uid} - ${user.data.name} (${this.formatTime(
                user.timestamp
            )})`;
            // 添加选中状态：最新的一条默认选中
            // if (index === 0) {
            //     option.selected = true;
            // }
            uidSelect.appendChild(option);
        });

        // 添加手动输入选项
        const manualOption = document.createElement("option");
        manualOption.value = "manual";
        manualOption.textContent = "↪ 手动输入新UID...";
        uidSelect.appendChild(manualOption);

        // 添加下拉框样式
        this.addSelectArrowStyle();

        // 显示提示
        this.showToast(
            `检测到${cachedUsers.length}个B站账号，请选择或手动输入`,
            "info"
        );

        // 如果默认选中了账号，自动填充
        // if (cachedUsers.length > 0) {
        //     setTimeout(() => {
        //         this.fillFromCache(cachedUsers[0].uid, true);
        //     }, 100);
        // }
    }

    // 添加下拉框箭头样式
    addSelectArrowStyle() {
        // 检查是否已经添加过样式
        if (document.getElementById("bili-select-style")) return;

        const style = document.createElement("style");
        style.id = "bili-select-style";
        style.textContent = `
        #bilibili_uid_select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 9L2 5h8z' fill='%23333'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            padding-right: 30px;
        }
        #bilibili_uid_select:focus {
            border-color: #00a1d6;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(0, 161, 214, 0.2);
        }
        #bilibili_uid_select option {
            padding: 8px;
            font-size: 14px;
        }
        #bilibili_uid_select option[value="manual"] {
            color: #00a1d6;
            font-weight: bold;
            border-top: 1px dashed #ddd;
            margin-top: 5px;
            padding-top: 10px;
        }
    `;
        document.head.appendChild(style);
    }

    // 从缓存填充
    fillFromCache(uid, showConfirm = true) {
        const cachedUser = this.getCachedUserByUid(uid);
        if (!cachedUser) return;

        const uidInput = document.getElementById("bilibili_uid");
        if (uidInput) {
            uidInput.value = uid;
            this.lastUid = uid;
        }

        if (showConfirm) {
            // 显示确认对话框
            setTimeout(() => {
                this.showBilibiliConfirmDialog(uid, cachedUser.data, true);
            }, 100);
        } else {
            // 直接填充，不显示确认
            this.fillUserInfo(uid, cachedUser.data, true);
        }
    }

    handleSelectChange(e) {
        const selectedValue = e.target.value;

        if (selectedValue === "manual") {
            // 切换到手动输入
            const uidInput = document.getElementById("bilibili_uid");
            const uidSelect = document.getElementById("bilibili_uid_select");

            if (uidInput && uidSelect) {
                uidSelect.style.display = "none";
                uidInput.style.display = "block";
                uidInput.focus();

                // 清空输入框
                uidInput.value = "";
                this.clearBilibiliData();

                // 添加一个按钮或提示，让用户可以切换回下拉框
                this.addBackToSelectButton();
            }
        } else if (selectedValue) {
            // 选择了缓存账号
            this.fillFromCache(selectedValue);
        }
    }

    // 添加返回下拉框的按钮
    addBackToSelectButton() {
        // 移除已有的返回按钮
        const existingBtn = document.querySelector(".back-to-select-btn");
        if (existingBtn) existingBtn.remove();

        const uidInput = document.getElementById("bilibili_uid");
        if (!uidInput) return;

        // 创建返回按钮
        const backButton = document.createElement("button");
        backButton.type = "button";
        backButton.className = "back-to-select-btn";
        backButton.innerHTML =
            '<i class="fa-solid fa-arrow-left"></i> 返回账号选择';
        backButton.style.cssText = `
        display: block;
        width: 100%;
        margin-top: 5px;
        padding: 6px 10px;
        font-size: 12px;
        color: #666;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    `;

        // 悬停效果
        backButton.addEventListener("mouseover", () => {
            backButton.style.background = "#eee";
            backButton.style.color = "#333";
        });
        backButton.addEventListener("mouseout", () => {
            backButton.style.background = "#f5f5f5";
            backButton.style.color = "#666";
        });

        // 点击事件 - 切换回下拉框
        backButton.addEventListener("click", () => {
            const uidInput = document.getElementById("bilibili_uid");
            const uidSelect = document.getElementById("bilibili_uid_select");

            if (uidInput && uidSelect) {
                uidInput.style.display = "none";
                uidSelect.style.display = "block";

                // 重置选择框的值
                uidSelect.value = "";

                // 清空B站数据
                this.clearBilibiliData();

                // 移除返回按钮
                backButton.remove();
            }
        });

        // 插入按钮
        uidInput.parentNode.insertBefore(backButton, uidInput.nextSibling);
    }

    handleUIDChange(e) {
        const currentUid = e.target.value.trim();
        if (currentUid && currentUid !== this.lastUid) {
            this.lastUid = currentUid;
            this.fetchBilibiliInfo(currentUid);
        }
    }

    handleUIDKeyPress(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const currentUid = e.target.value.trim();
            if (currentUid && currentUid !== this.lastUid) {
                this.lastUid = currentUid;
                this.fetchBilibiliInfo(currentUid);
            }
        }
    }

    // 根据UID获取特定缓存
    getCachedUserByUid(uid) {
        try {
            const cacheKey = this.cacheKeyPrefix + uid;
            const cacheData = localStorage.getItem(cacheKey);

            if (cacheData) {
                const parsed = JSON.parse(cacheData);
                if (this.isCacheValid(parsed.timestamp)) {
                    return parsed;
                } else {
                    // 缓存过期，清除
                    localStorage.removeItem(cacheKey);
                }
            }
            return null;
        } catch (e) {
            console.error("读取UID缓存失败:", e);
            return null;
        }
    }

    // 检查缓存是否有效
    isCacheValid(timestamp) {
        if (!timestamp) return false;

        const expiryTime = this.cacheExpiryDays * 24 * 60 * 60 * 1000; // 转换为毫秒
        const now = Date.now();

        return now - timestamp < expiryTime;
    }

    // 格式化时间
    formatTime(timestamp) {
        const now = Date.now();
        const diff = now - timestamp;
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));

        if (days === 0) return "今天";
        if (days === 1) return "昨天";
        return `${days}天前`;
    }

    async fetchBilibiliInfo(uid) {
        if (!uid || !/^\d+$/.test(uid)) {
            return;
        }

        // 首先检查缓存
        const cachedUser = this.getCachedUserByUid(uid);
        if (cachedUser) {
            console.log("使用缓存的用户信息:", cachedUser);
            this.showBilibiliConfirmDialog(uid, cachedUser.data);
            return;
        }

        const loadingEl = document.getElementById("bili_loading");
        const uidInput = document.getElementById("bilibili_uid");

        loadingEl.style.display = "block";
        uidInput.disabled = true;

        try {
            // 使用代理API绕过CORS限制
            const response = await fetch(
                `https://uapis.cn/api/v1/social/bilibili/userinfo?uid=${uid}`,
                {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                    },
                    mode: "cors",
                    credentials: "omit",
                }
            );

            if (!response.ok) {
                const errorMsg = this.getErrorMessage(response.status);
                throw new Error(`HTTP ${response.status}: ${errorMsg}`);
            }

            const data = await response.json();

            if (data && data.name) {
                // 处理头像URL，确保使用正确的referrerpolicy
                const processedData = {
                    ...data,
                    // 确保头像URL是有效的HTTPS链接
                    face: this.processAvatarUrl(data.face || ""),
                };

                // 保存当前用户数据（用于后续保存）
                this.currentUserData = processedData;

                this.showBilibiliConfirmDialog(uid, processedData);
            } else {
                this.showToast("获取失败：返回数据格式错误", "error");
            }
        } catch (error) {
            console.error("获取B站信息失败:", error);
            const userMessage = this.getUserFriendlyErrorMessage(error);
            this.showToast(userMessage, "error");
        } finally {
            loadingEl.style.display = "none";
            uidInput.disabled = false;
        }
    }

    // 处理头像URL，确保使用HTTPS
    processAvatarUrl(url) {
        if (!url) return "";
        // 确保使用HTTPS
        let processedUrl = url.replace("http://", "https://");
        return processedUrl;
    }

    getErrorMessage(status) {
        const errorMap = {
            500: "B站信息查询服务暂时不可用(500)，请稍后重试",
            502: "B站接口繁忙(502)，请稍后重试",
            404: "未找到此UID对应的B站用户",
            429: "请求过于频繁，请稍后重试",
        };
        return errorMap[status] || "API请求失败";
    }

    getUserFriendlyErrorMessage(error) {
        if (error.message.includes("HTTP 500")) {
            return "服务暂时不稳定，请稍后重试";
        } else if (error.message.includes("HTTP 502")) {
            return "B站接口繁忙，请稍后再试";
        } else if (error.message.includes("HTTP 404")) {
            return "未找到该B站用户，请检查UID";
        } else if (error.message.includes("HTTP 429")) {
            return "操作太快了，请稍等片刻再试";
        } else if (
            error.message.includes("Failed to fetch") ||
            error.message.includes("NetworkError")
        ) {
            return "网络连接失败，请检查网络";
        }
        return "网络请求失败，请重试";
    }

    showBilibiliConfirmDialog(uid, userData, fromCache = false) {
        // 移除已有的弹窗
        const oldDialog = document.getElementById("bili-confirm-dialog");
        if (oldDialog) oldDialog.remove();

        const dialog = document.createElement("div");
        dialog.id = "bili-confirm-dialog";
        dialog.innerHTML = this.createConfirmDialogHTML(uid, userData, fromCache);

        document.body.appendChild(dialog);

        // 绑定事件
        this.bindDialogEvents(dialog, uid, userData, fromCache);
    }

    createConfirmDialogHTML(uid, userData, fromCache = false) {
        // 为确认弹窗中的头像也设置referrerpolicy
        const avatarUrl = userData.face;
        const cacheNote = fromCache
            ? `<div style="
                    font-size: 12px;
                    color: #666;
                    background: #f0f8ff;
                    padding: 4px 8px;
                    border-radius: 4px;
                    margin-top: 4px;
                    display: inline-block;
                ">
                    <i class="fa-solid fa-clock"></i> 来自本地缓存
                </div>`
            : "";

        // 新增：根据 fromCache 决定是否显示保存选项
        const saveOptionHTML = fromCache
            ? ""
            : `
                <!-- 添加保存选项 -->
                <div class="save-option" style="
                    margin-bottom: 20px;
                    padding: 12px;
                    background: #f8f9fa;
                    border-radius: 8px;
                    border: 1px solid #e9ecef;
                ">
                    <label style="
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        cursor: pointer;
                        font-size: 14px;
                        color: #333;
                    ">
                        <input type="checkbox" id="save_account_dialog" 
                            style="width: 16px; height: 16px; cursor: pointer;">
                        <i class="fa-solid fa-save" style="color: #00a1d6;"></i>
                        <span>保存此B站账号到本地（方便下次使用）</span>
                    </label>
                    <div style="
                        font-size: 12px;
                        color: #666;
                        margin-top: 6px;
                        margin-left: 24px;
                    ">
                        注意：账号信息仅保存在您的浏览器中，不会上传到服务器
                    </div>
                </div>
            `;

        return `
            <div class="bili-dialog-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            ">
                <div class="bili-dialog-content" style="
                    background: white;
                    border-radius: 12px;
                    padding: 24px;
                    width: 90%;
                    max-width: 400px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                    animation: scaleIn 0.3s ease;
                ">
                    <h3 style="
                        margin-top: 0;
                        margin-bottom: 16px;
                        color: #333;
                        font-size: 18px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                        <i class="fa-solid fa-user-check" style="color: #00a1d6;"></i>
                        确认B站账号信息
                    </h3>
                    
                    <div class="bili-user-info" style="
                        display: flex;
                        align-items: center;
                        gap: 16px;
                        margin-bottom: 20px;
                        padding: 16px;
                        background: #f8f9fa;
                        border-radius: 8px;
                        border-left: 4px solid #00a1d6;
                    ">
                        <img src="${avatarUrl}" 
                            style="
                                width: 60px;
                                height: 60px;
                                border-radius: 50%;
                                border: 2px solid #00a1d6;
                                object-fit: cover;
                            "
                            alt="${userData.name}的头像"
                            crossorigin="anonymous"
                            referrerpolicy="no-referrer">
                        <div>
                            <div style="
                                font-weight: bold;
                                font-size: 16px;
                                color: #333;
                                margin-bottom: 4px;
                            ">
                                ${userData.name}
                            </div>
                            <div style="
                                font-size: 14px;
                                color: #666;
                                margin-bottom: 4px;
                            ">
                                UID: ${uid}
                            </div>
                            <div style="
                                font-size: 13px;
                                color: #888;
                            ">
                                B站等级: ${userData.level || "未知"}
                            </div>
                            ${cacheNote}
                        </div>
                    </div>
                    
                    ${saveOptionHTML}
                    
                    <div class="bili-dialog-actions" style="
                        display: flex;
                        gap: 12px;
                        justify-content: flex-end;
                    ">
                        <button type="button" class="bili-btn-cancel" style="
                            padding: 10px 20px;
                            background: #f5f5f5;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            color: #666;
                            cursor: pointer;
                            font-size: 14px;
                            transition: all 0.2s ease;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        ">
                            <i class="fa-solid fa-times"></i>
                            取消
                        </button>
                        <button type="button" class="bili-btn-confirm" style="
                            padding: 10px 20px;
                            background: #00a1d6;
                            border: none;
                            border-radius: 6px;
                            color: white;
                            cursor: pointer;
                            font-size: 14px;
                            transition: all 0.2s ease;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        ">
                            <i class="fa-solid fa-check"></i>
                            确认使用
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    bindDialogEvents(dialog, uid, userData, fromCache = false) {
        // 添加 fromCache 参数
        const confirmBtn = dialog.querySelector(".bili-btn-confirm");
        const cancelBtn = dialog.querySelector(".bili-btn-cancel");
        const overlay = dialog.querySelector(".bili-dialog-overlay");
        const saveCheckbox = dialog.querySelector("#save_account_dialog");

        // 确认按钮事件
        confirmBtn.addEventListener("click", () => {
            this.closeDialog(dialog);
            this.fillUserInfo(uid, userData);

            // 检查确认对话框中是否勾选了保存选项（只有当不是从缓存加载时才存在）
            if (!fromCache && saveCheckbox && saveCheckbox.checked) {
                // 立即保存用户信息
                this.saveUserToStorage(uid);
            }

            this.showToast("B站信息加载成功！", "success");
        });

        // 取消按钮事件
        cancelBtn.addEventListener("click", () => {
            this.closeDialog(dialog);
            this.clearBilibiliData();
            this.showToast("已取消，请重新填写", "info");
        });

        // 遮罩层点击事件
        overlay.addEventListener("click", (e) => {
            if (e.target === overlay) {
                this.closeDialog(dialog);
                this.clearBilibiliData();
                this.showToast("已取消，请重新填写", "info");
            }
        });

        // 添加悬停效果
        this.addButtonHoverEffects(confirmBtn, cancelBtn);
    }

    addButtonHoverEffects(confirmBtn, cancelBtn) {
        confirmBtn.addEventListener("mouseover", () => {
            confirmBtn.style.background = "#008db2";
        });
        confirmBtn.addEventListener("mouseout", () => {
            confirmBtn.style.background = "#00a1d6";
        });

        cancelBtn.addEventListener("mouseover", () => {
            cancelBtn.style.background = "#eee";
        });
        cancelBtn.addEventListener("mouseout", () => {
            cancelBtn.style.background = "#f5f5f5";
        });
    }

    closeDialog(dialog) {
        dialog.style.animation = "fadeOut 0.3s ease";
        setTimeout(() => {
            if (dialog.parentNode) dialog.remove();
        }, 300);
    }

    fillUserInfo(uid, data, isAutoFill = false) {
        // 先填充文本字段
        const authorInput = document.getElementById("author");
        if (authorInput) {
            authorInput.value = data.name;
            authorInput.setAttribute("data-from-bilibili", "true");
        }

        const urlInput = document.getElementById("url");
        if (urlInput) {
            urlInput.value = `https://space.bilibili.com/${uid}`;
            urlInput.setAttribute("data-from-bilibili", "true");
        }

        // 延迟头像加载，确保弹窗完全关闭
        setTimeout(
            () => {
                const avatarImg = document.querySelector(".comment-user-avatar img");
                if (avatarImg) {
                    // 保存原始头像
                    if (!this.originalAvatarSrc) {
                        this.originalAvatarSrc = avatarImg.src;
                    }

                    // 先添加加载样式
                    avatarImg.classList.add("avatar-loading");

                    // 预加载头像
                    this.loadAvatarWithReferrerPolicy(avatarImg, data.face, data.name)
                        .then(() => {
                            avatarImg.classList.remove("avatar-loading");
                            avatarImg.classList.add("avatar-loaded");
                        })
                        .catch((err) => {
                            console.error("头像加载失败:", err);
                            avatarImg.classList.remove("avatar-loading");
                            this.loadAvatarViaProxy(avatarImg, data.face, data.name);
                        });
                }
            },
            isAutoFill ? 0 : 300
        ); // 自动填充不需要等待

        // 添加隐藏字段
        this.addHiddenField("bilibili_avatar_hidden", "bilibili_avatar", data.face);
        this.addHiddenField("bilibili_uid_hidden", "bilibili_uid", uid);

        if (data.level !== undefined) {
            this.addHiddenField(
                "bilibili_level_hidden",
                "bilibili_level",
                data.level
            );
        }
    }

    // 使用Image对象预加载头像并设置referrerpolicy
    loadAvatarWithReferrerPolicy(imgElement, avatarUrl, userName) {
        return new Promise((resolve, reject) => {
            const tempImg = new Image();

            // 设置referrerpolicy
            tempImg.referrerPolicy = "no-referrer";
            tempImg.crossOrigin = "anonymous";

            tempImg.onload = () => {
                // 预加载成功后设置到实际元素
                imgElement.src = avatarUrl;
                imgElement.setAttribute("referrerpolicy", "no-referrer");
                imgElement.setAttribute("crossorigin", "anonymous");
                imgElement.alt = userName + "的B站头像";
                imgElement.setAttribute("data-from-bilibili", "true");
                resolve();
            };

            tempImg.onerror = (err) => {
                console.warn("直接加载头像失败:", err);
                reject(err);
            };

            // 开始加载
            tempImg.src = avatarUrl;
        });
    }

    // 备用方案：通过代理加载头像
    loadAvatarViaProxy(imgElement, originalUrl, userName) {
        // 使用图片代理服务（需要你自己部署或使用可用的服务）
        const proxyUrl = `https://images.weserv.nl/?url=${encodeURIComponent(
            originalUrl
        )}&w=100&h=100&output=webp`;

        imgElement.src = proxyUrl;
        imgElement.alt = userName + "的B站头像";
        imgElement.setAttribute("data-from-bilibili", "true");
        imgElement.setAttribute("data-proxy-used", "true");
    }

    addHiddenField(id, name, value) {
        let field = document.getElementById(id);
        const form = document.getElementById("commentform");

        if (!form) return;

        if (!field) {
            field = document.createElement("input");
            field.type = "hidden";
            field.id = id;
            field.name = name;
            field.value = value;
            form.appendChild(field);
        } else {
            field.value = value;
        }
    }

    clearBilibiliData() {
        // 清空UID输入框或选择框
        const uidInput = document.getElementById("bilibili_uid");
        const uidSelect = document.getElementById("bilibili_uid_select");
        const backButton = document.querySelector(".back-to-select-btn");
        if (backButton) backButton.remove();

        if (uidInput) {
            uidInput.value = "";
            uidInput.focus();
        }

        if (uidSelect) {
            uidSelect.value = "";
        }

        // 清空昵称（如果是B站来源的）
        const authorInput = document.getElementById("author");
        if (
            authorInput &&
            authorInput.getAttribute("data-from-bilibili") === "true"
        ) {
            authorInput.value = "";
            authorInput.removeAttribute("data-from-bilibili");
        }

        // 清空网站（如果是B站来源的）
        const urlInput = document.getElementById("url");
        if (urlInput && urlInput.getAttribute("data-from-bilibili") === "true") {
            urlInput.value = "";
            urlInput.removeAttribute("data-from-bilibili");
        }

        // 恢复原始头像
        const avatarImg = document.querySelector(".comment-user-avatar img");
        if (avatarImg && this.originalAvatarSrc) {
            // 移除所有B站相关属性
            avatarImg.removeAttribute("data-from-bilibili");
            avatarImg.removeAttribute("data-proxy-used");
            avatarImg.removeAttribute("referrerpolicy");
            avatarImg.removeAttribute("crossorigin");
            avatarImg.alt = "comment_user_avatar";

            // 恢复原始头像
            avatarImg.src = this.originalAvatarSrc;
        }

        // 移除隐藏字段
        const hiddenFields = [
            "bilibili_avatar_hidden",
            "bilibili_uid_hidden",
            "bilibili_level_hidden",
        ];

        hiddenFields.forEach((id) => {
            const field = document.getElementById(id);
            if (field && field.parentNode) {
                field.parentNode.removeChild(field);
            }
        });

        this.lastUid = "";
    }

    showToast(message, type = "info") {
        // 移除已有的提示
        const oldToast = document.getElementById("bili-toast");
        if (oldToast) oldToast.remove();

        // 创建提示元素
        const toast = document.createElement("div");
        toast.id = "bili-toast";
        toast.innerHTML = this.createToastHTML(message, type);

        document.body.appendChild(toast);

        // 3秒后自动移除
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = "slideOut 0.3s ease";
                setTimeout(() => {
                    if (toast.parentNode) toast.remove();
                }, 300);
            }
        }, 3000);
    }

    createToastHTML(message, type) {
        const icons = {
            success: "fa-check-circle",
            error: "fa-exclamation-circle",
            info: "fa-info-circle",
        };

        const colors = {
            success: "#4CAF50",
            error: "#f44336",
            info: "#2196F3",
        };

        return `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${colors[type] || colors.info};
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
            ">
                <i class="fa-solid ${icons[type] || icons.info}" 
                style="margin-right:8px;"></i>
                ${message}
            </div>
        `;
    }

    addStyles() {
        if (document.getElementById("bili-toast-style")) return;

        const style = document.createElement("style");
        style.id = "bili-toast-style";
        style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .bilibili-popup {
            position: relative !important;
        }
        #bilibili_uid {
            padding-right: 30px !important;
        }
        /* 为B站头像添加特殊标识 */
        img[data-from-bilibili="true"] {
            border: 2px solid #00a1d6 !important;
            position: relative;
            transition: border-color 0.3s ease;
        }
        img[data-from-bilibili="true"]::after {
            content: 'B';
            position: absolute;
            top: -8px;
            right: -8px;
            background: #00a1d6;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            z-index: 10;
        }
        /* 头像加载动画 */
        .avatar-loading {
            opacity: 0.5;
            filter: blur(2px);
            transition: opacity 0.3s ease, filter 0.3s ease;
        }
        .avatar-loaded {
            opacity: 1;
            filter: none;
        }
        /* 下拉框箭头样式 */
        #bilibili_uid_select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 9L2 5h8z' fill='%23333'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            padding-right: 30px;
        }
        #bilibili_uid_select:focus {
            border-color: #00a1d6;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(0, 161, 214, 0.2);
        }
        /* 下拉框选项样式 */
        #bilibili_uid_select option {
            padding: 8px;
            font-size: 14px;
        }
        /* 手动输入选项特殊样式 */
        #bilibili_uid_select option[value="manual"] {
            color: #00a1d6;
            font-weight: bold;
            border-top: 1px solid #eee;
            margin-top: 5px;
            padding-top: 10px;
        }
    `;
        document.head.appendChild(style);
    }
}

// ES模块导出
export default BilibiliUIDFiller;

// 全局变量导出
if (typeof window !== 'undefined') {
    window.BilibiliUIDFiller = BilibiliUIDFiller;
}