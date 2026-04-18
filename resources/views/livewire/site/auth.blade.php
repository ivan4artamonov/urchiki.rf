<div class="flex flex-1 flex-col">
	<x-site.breadcrumbs :items="[
		'Вход / Регистрация' => '',
	]" />

	<div class="mx-auto w-full max-w-[420px] flex-1 pt-6 md:pt-10">
		<x-site.page-title align="center">
			Войти в Урчики
		</x-site.page-title>
		<p class="mt-2 text-center text-[15px] leading-snug text-urchiki-muted">
			На указанный адрес придёт код из 4 цифр.<br />Введите его, чтобы войти.
		</p>

		@if ($step === 'email')
			<form wire:submit="sendCode" class="mt-8 space-y-5">
				<x-site.input
					id="auth-email"
					label="Электронная почта"
					type="email"
					error-field="form.email"
					wire:model="form.email"
					autocomplete="email"
				/>
				<x-site.button type="submit" variant="primary">
					Получить код
				</x-site.button>
			</form>
		@else
			<div class="mt-6 rounded-xl border border-urchiki-border bg-urchiki-accent-light px-4 py-3 text-center text-sm text-urchiki-text-sec">
				Код отправлен на адрес <strong class="text-urchiki-text">{{ $form->email }}</strong>
			</div>
			<form wire:submit="verifyCode" class="mt-6 space-y-5">
				<x-site.input type="hidden" wire:model="form.email" autocomplete="username" />
				<x-site.input
					id="auth-code"
					label="Код из письма"
					type="text"
					error-field="form.code"
					wire:model="form.code"
					class="text-center font-site-heading text-2xl tracking-[0.35em]"
					inputmode="numeric"
					pattern="[0-9]*"
					maxlength="4"
					autocomplete="one-time-code"
					placeholder="0000"
				/>
				<x-site.button type="submit" variant="primary">
					Войти
				</x-site.button>
			</form>
			<div class="mt-4 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm">
				<x-site.button type="button" variant="link-accent" wire:click="resendCode">
					Отправить код снова
				</x-site.button>
				<x-site.button type="button" variant="link-muted" wire:click="backToEmail">
					Изменить адрес электронной почты
				</x-site.button>
			</div>
		@endif

		<div class="mt-10 text-center text-sm text-urchiki-muted">или через соцсети</div>
		<div class="mt-3 flex gap-2.5">
			<x-site.social-login-button icon="vk">ВКонтакте</x-site.social-login-button>
			<x-site.social-login-button icon="yandex">Яндекс</x-site.social-login-button>
			<x-site.social-login-button icon="maildotru">Mail.ru</x-site.social-login-button>
		</div>
		<p class="mt-3 text-center text-xs leading-relaxed text-urchiki-muted">
			Вход через соцсети подключим позже — по сценарию из прототипа.
		</p>
	</div>
</div>
