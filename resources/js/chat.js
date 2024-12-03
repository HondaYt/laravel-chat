document.addEventListener("DOMContentLoaded", () => {
	// 未認証の場合は早期リターン
	if (!window.userId || !window.userName) {
		console.log("ユーザーが認証されていません");
		return;
	}

	// WebSocket接続の設定を最適化
	Echo.connector.pusher.config.activityTimeout = 5000; // 5秒
	Echo.connector.pusher.config.pongTimeout = 3000; // 3秒
	Echo.connector.pusher.config.unavailableTimeout = 5000; // 5秒

	// WebSocket接続状態の監視を追加
	Echo.connector.pusher.connection.bind("connecting", () => {
		console.log("WebSocket: 接続中...");
	});

	Echo.connector.pusher.connection.bind("connected", () => {
		console.log("WebSocket: 接続完了");
	});

	Echo.connector.pusher.connection.bind("disconnected", () => {
		console.log("WebSocket: 切断");
	});

	Echo.connector.pusher.connection.bind("error", (error) => {
		console.error("WebSocket エラー:", error);
	});

	const sendButton = document.getElementById("send-button");
	if (sendButton) {
		sendButton.addEventListener("click", () => {
			const message = document.getElementById("message").value;
			if (message) {
				// 送信中の状態を表示
				const sendIcon = sendButton.querySelector("i");
				const messageInput = document.getElementById("message");
				sendButton.disabled = true;
				messageInput.disabled = true;
				sendIcon.className = "fas fa-spinner fa-spin";

				console.time("メッセージ送信時間");
				console.log("送信開始:", new Date().toISOString());

				// 即時メッセージ表示
				displayMessage({
					message: message,
					user: {
						id: window.userId,
						name: window.userName,
					},
				});

				axios
					.post("/", { message: message })
					.then(() => {
						console.log("サーバーレスポンス受信:", new Date().toISOString());
						document.getElementById("message").value = "";
						console.timeEnd("メッセージ送信時間");
					})
					.catch((error) => {
						console.error("送信エラー:", error);
						console.timeEnd("メッセージ送信時間");
					})
					.finally(() => {
						// 送信完了後、ボタンとフィールドを元の状態に戻す
						sendButton.disabled = false;
						messageInput.disabled = false;
						sendIcon.className = "fas fa-paper-plane";
					});
			}
		});
	}
});

// メッセージ表示用の関数を作成
function displayMessage(e) {
	console.log("メッセージ表示時刻:", new Date().toISOString());
	const messageWrapper = document.createElement("div");
	messageWrapper.classList.add("message-wrapper");

	if (e.user.id === window.userId) {
		messageWrapper.classList.add("message-mine");
	} else {
		messageWrapper.classList.add("message-others");
	}

	const messageInfo = document.createElement("div");
	messageInfo.classList.add("message-info");

	const userName = document.createElement("span");
	userName.classList.add("user-name");
	userName.textContent = e.user.name;
	messageInfo.appendChild(userName);

	const messageBubble = document.createElement("div");
	messageBubble.classList.add("message-bubble");
	messageBubble.textContent = e.message;

	const timeStamp = document.createElement("span");
	timeStamp.classList.add("message-time");
	const now = new Date();
	timeStamp.textContent = now.toLocaleString("ja-JP", {
		hour: "2-digit",
		minute: "2-digit",
	});
	messageBubble.appendChild(timeStamp);

	messageWrapper.appendChild(messageInfo);
	messageWrapper.appendChild(messageBubble);

	document.getElementById("message-list").appendChild(messageWrapper);

	const messageList = document.getElementById("message-list");
	messageList.scrollTop = messageList.scrollHeight;
}

Echo.channel("channel-chat").listen("ChatEvent", (e) => {
	console.log("メッセージ受信時刻:", new Date().toISOString());
	console.log("Current user ID:", window.userId);
	console.log("Message user ID:", e.user.id);

	// 自分が送信したメッセージは既に表示されているのでスキップ
	if (e.user.id === window.userId) return;

	displayMessage(e);
});
